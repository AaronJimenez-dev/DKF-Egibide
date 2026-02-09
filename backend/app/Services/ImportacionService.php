<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;

class ImportacionService {
    
    /**
     * Importa asignaciones de tutores y asignaturas desde archivo CSV
     * 
     * Guarda en la tabla 'asignaciones' la relación completa:
     * - Tutor que imparte
     * - Asignatura
     * - Ciclo
     * - Información del grupo (campus, modelo, régimen, descripción)
     * 
     * @param UploadedFile $file Archivo CSV con estructura Egibide
     * @return array Estadísticas de la importación
     */
    public function importarAsignaciones(UploadedFile $file): array 
    {
        // Leer y procesar el CSV
        $content = file_get_contents($file->getRealPath());
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        
        if ($encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }
        
        $delimiter = str_contains($content, ';') ? ';' : ',';
        $lines = explode("\n", str_replace("\r", "", trim($content)));
        $header = str_getcsv(array_shift($lines), $delimiter);
        $header[0] = preg_replace('/^[\xEF\xBB\xBF\xFF\xFE]/', '', $header[0]);
        
        $data = [];
        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            
            $fields = str_getcsv($line, $delimiter);
            if (count($header) == count($fields)) {
                $data[] = array_combine($header, $fields);
            }
        }
        
        return DB::transaction(function () use ($data) {
            return $this->procesarAsignaciones($data);
        });
    }
    
    /**
     * Procesa los datos de asignaciones
     */
    private function procesarAsignaciones(array $data): array 
    {
        $stats = [
            'usuarios_creados' => 0,
            'usuarios_actualizados' => 0,
            'tutores_creados' => 0,
            'tutores_actualizados' => 0,
            'ciclos_creados' => 0,
            'ciclos_actualizados' => 0,
            'asignaturas_creadas' => 0,
            'asignaturas_actualizadas' => 0,
            'asignaciones_creadas' => 0,
            'asignaciones_actualizadas' => 0,
            'filas_procesadas' => 0,
            'errores' => []
        ];
        
        // Cache para evitar consultas repetidas
        $tutoresCache = [];
        $ciclosCache = [];
        $asignaturasCache = [];
        
        foreach ($data as $index => $row) {
            try {
                // Validar datos obligatorios
                if (empty($row['Alias_Profesor']) || empty($row['Des_Asig']) || empty($row['Grupo'])) {
                    $stats['errores'][] = "Fila " . ($index + 2) . ": Campos obligatorios vacíos";
                    continue;
                }
                
                $aliasProfesor = trim($row['Alias_Profesor']);
                $nombreAsignatura = trim($row['Des_Asig']);
                $codigoGrupo = trim($row['Grupo']);
                $campus = trim($row['Campus'] ?? '');
                $modelo = trim($row['Modelo'] ?? '');
                $regimen = trim($row['Regimen'] ?? '');
                $descripcionGrupo = trim($row['Des_Grupo'] ?? '');
                
                // 1. PROCESAR TUTOR (Usuario + Perfil)
                $tutorId = $this->procesarTutor(
                    $aliasProfesor,
                    trim($row['Nombre'] ?? ''),
                    trim($row['Apel1'] ?? ''),
                    trim($row['Apel2'] ?? ''),
                    $tutoresCache,
                    $stats
                );
                
                // 2. PROCESAR CICLO
                $cicloId = $this->procesarCiclo(
                    $codigoGrupo,
                    $descripcionGrupo,
                    $ciclosCache,
                    $stats
                );
                
                // 3. PROCESAR ASIGNATURA
                $asignaturaId = $this->procesarAsignatura(
                    $nombreAsignatura,
                    $codigoGrupo,
                    $cicloId,
                    $asignaturasCache,
                    $stats
                );
                
                // 4. CREAR/ACTUALIZAR ASIGNACIÓN COMPLETA
                $this->guardarAsignacion(
                    $tutorId,
                    $asignaturaId,
                    $cicloId,
                    $campus,
                    $codigoGrupo,
                    $modelo,
                    $regimen,
                    $descripcionGrupo,
                    $stats
                );
                
                $stats['filas_procesadas']++;
                
            } catch (Exception $e) {
                $stats['errores'][] = "Fila " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        return $stats;
    }
    
    /**
     * Procesa un tutor (crea o actualiza usuario y perfil)
     */
    private function procesarTutor(
        string $alias,
        string $nombre,
        string $apellido1,
        string $apellido2,
        array &$cache,
        array &$stats
    ): int {
        // Usar cache para evitar consultas repetidas
        if (isset($cache[$alias])) {
            return $cache[$alias];
        }
        
        // 1. Crear o actualizar Usuario
        $email = strtolower($alias) . "@egibide.org";
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            // Actualizar role si es necesario
            if ($user->role !== 'tutor_egibide') {
                $user->update(['role' => 'tutor_egibide']);
            }
            $stats['usuarios_actualizados']++;
        } else {
            $user = User::create([
                'email' => $email,
                'password' => Hash::make('egibide2025'),
                'role' => 'tutor_egibide'
            ]);
            $stats['usuarios_creados']++;
        }
        
        // 2. Crear o actualizar perfil Tutor
        $apellidos = trim($apellido1 . ' ' . $apellido2);
        
        $tutor = DB::table('tutores')->where('user_id', $user->id)->first();
        
        if ($tutor) {
            DB::table('tutores')
                ->where('id', $tutor->id)
                ->update([
                    'nombre' => $nombre,
                    'apellidos' => $apellidos,
                    'alias' => $alias,
                    'updated_at' => now()
                ]);
            $stats['tutores_actualizados']++;
            $tutorId = $tutor->id;
        } else {
            $tutorId = DB::table('tutores')->insertGetId([
                'user_id' => $user->id,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'alias' => $alias,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $stats['tutores_creados']++;
        }
        
        $cache[$alias] = $tutorId;
        return $tutorId;
    }
    
    /**
     * Procesa un ciclo (grupo/clase)
     * Busca por código o crea uno nuevo vinculado a una familia profesional
     */
    private function procesarCiclo(
        string $codigo,
        string $descripcion,
        array &$cache,
        array &$stats
    ): int {
        // Usar cache
        if (isset($cache[$codigo])) {
            return $cache[$codigo];
        }
        
        $ciclo = DB::table('ciclos')->where('codigo', $codigo)->first();
        
        if ($ciclo) {
            // Actualizar nombre/descripción si ha cambiado
            DB::table('ciclos')
                ->where('id', $ciclo->id)
                ->update([
                    'nombre' => $descripcion,
                    'updated_at' => now()
                ]);
            $stats['ciclos_actualizados']++;
            $cicloId = $ciclo->id;
        } else {
            // Crear nuevo ciclo
            // NOTA: Necesitas tener una familia_profesional_id por defecto
            // o extraerla del código del grupo
            
            // Opción 1: Usar una familia por defecto
            $familiaPorDefecto = DB::table('familias_profesionales')->first();
            
            if (!$familiaPorDefecto) {
                throw new Exception("No hay familias profesionales en la base de datos. Crea al menos una antes de importar.");
            }
            
            $cicloId = DB::table('ciclos')->insertGetId([
                'codigo' => $codigo,
                'nombre' => $descripcion,
                'familia_profesional_id' => $familiaPorDefecto->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $stats['ciclos_creados']++;
        }
        
        $cache[$codigo] = $cicloId;
        return $cicloId;
    }
    
    /**
     * Procesa una asignatura
     */
    private function procesarAsignatura(
        string $nombre,
        string $codigoGrupo,
        int $cicloId,
        array &$cache,
        array &$stats
    ): int {
        // Cache key: nombre + ciclo
        $cacheKey = $cicloId . '_' . $nombre;
        
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }
        
        // Generar código de asignatura
        $codigoAsignatura = $codigoGrupo . "-" . substr(md5($nombre), 0, 4);
        
        // Buscar por nombre y ciclo
        $asignatura = DB::table('asignaturas')
            ->where('nombre_asignatura', $nombre)
            ->where('ciclo_id', $cicloId)
            ->first();
        
        if ($asignatura) {
            // Actualizar código si es necesario
            DB::table('asignaturas')
                ->where('id', $asignatura->id)
                ->update([
                    'codigo_asignatura' => $codigoAsignatura,
                    'updated_at' => now()
                ]);
            $stats['asignaturas_actualizadas']++;
            $asignaturaId = $asignatura->id;
        } else {
            $asignaturaId = DB::table('asignaturas')->insertGetId([
                'codigo_asignatura' => $codigoAsignatura,
                'nombre_asignatura' => $nombre,
                'ciclo_id' => $cicloId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $stats['asignaturas_creadas']++;
        }
        
        $cache[$cacheKey] = $asignaturaId;
        return $asignaturaId;
    }
    
    /**
     * Guarda la asignación completa con toda la información del grupo
     */
    private function guardarAsignacion(
        int $tutorId,
        int $asignaturaId,
        int $cicloId,
        string $campus,
        string $grupo,
        string $modelo,
        string $regimen,
        string $descripcionGrupo,
        array &$stats
    ): void {
        // Buscar si ya existe esta asignación
        $asignacion = DB::table('asignaciones')
            ->where('tutor_id', $tutorId)
            ->where('asignatura_id', $asignaturaId)
            ->where('grupo', $grupo)
            ->first();
        
        $datosAsignacion = [
            'tutor_id' => $tutorId,
            'asignatura_id' => $asignaturaId,
            'ciclo_id' => $cicloId,
            'campus' => $campus,
            'grupo' => $grupo,
            'modelo' => $modelo,
            'regimen' => $regimen,
            'descripcion_grupo' => $descripcionGrupo,
            'updated_at' => now()
        ];
        
        if ($asignacion) {
            // Actualizar la asignación existente
            DB::table('asignaciones')
                ->where('id', $asignacion->id)
                ->update($datosAsignacion);
            $stats['asignaciones_actualizadas']++;
        } else {
            // Crear nueva asignación
            $datosAsignacion['created_at'] = now();
            DB::table('asignaciones')->insert($datosAsignacion);
            $stats['asignaciones_creadas']++;
        }
    }
    
    // ============================================================
    // MÉTODO GENÉRICO DE IMPORTACIÓN
    // ============================================================
    
    public function importar(UploadedFile $file): array {
        $extension = $file->getClientOriginalExtension();
        $data = [];
        $header = [];

        if (in_array($extension, ['xlsx', 'xls'])) {
            // Lógica para EXCEL
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            $header = array_shift($rows);
            foreach ($rows as $row) {
                if (isset($row[0]) && !empty($row[0])) {
                    $data[] = array_combine($header, $row);
                }
            }
        } else {
            // Lógica para CSV
            $content = file_get_contents($file->getRealPath());
            $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
            if ($encoding !== 'UTF-8') {
                $content = mb_convert_encoding($content, 'UTF-8', $encoding);
            }
            $delimiter = str_contains($content, ';') ? ';' : ',';
            $lines = explode("\n", str_replace("\r", "", trim($content)));
            $header = str_getcsv(array_shift($lines), $delimiter);
            $header[0] = preg_replace('/^[\xEF\xBB\xBF\xFF\xFE]/', '', $header[0]);

            foreach ($lines as $line) {
                $fields = str_getcsv($line, $delimiter);
                if (count($header) == count($fields)) {
                    $data[] = array_combine($header, $fields);
                }
            }
        }

        return DB::transaction(function () use ($data, $header) {
            if (in_array('DNI ALUMNO', $header)) {
                return $this->procesarAlumnos($data);
            } 
            if (in_array('Alias_Profesor', $header)) {
                return $this->procesarAsignaciones($data);
            }
            throw new Exception("Formato de columnas no reconocido.");
        });
    }

    private function procesarAlumnos(array $data): array {
        $cont = 0;
        foreach ($data as $row) {
            $user = User::updateOrCreate(
                ['email' => $row['EMAIL ALUMNO']],
                [
                    'password' => Hash::make($row['DNI ALUMNO']),
                    'role' => 'alumno'
                ]
            );

            $dni = trim($row['DNI ALUMNO']);
            $existeAlumno = DB::table('alumnos')->where('dni', $dni)->first();

            if ($existeAlumno) {
                DB::table('alumnos')->where('id', $existeAlumno->id)->update([
                    'user_id'      => $user->id,
                    'nombre'       => $row['NOMBRE ALUMNO'],
                    'apellidos'    => $row['APELLIDO1 ALUMNO'] . ' ' . $row['APELLIDO2 ALUMNO'],
                    'matricula_id' => $row['MATRICULA ALUMNO'],
                    'updated_at'   => now()
                ]);
            } else {
                DB::table('alumnos')->insert([
                    'user_id'      => $user->id,
                    'dni'          => $dni,
                    'nombre'       => $row['NOMBRE ALUMNO'],
                    'apellidos'    => $row['APELLIDO1 ALUMNO'] . ' ' . $row['APELLIDO2 ALUMNO'],
                    'matricula_id' => $row['MATRICULA ALUMNO'],
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);
            }
            $cont++;
        }
        return ['tipo' => 'Alumnos', 'cantidad' => $cont];
    }
}
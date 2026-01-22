<?php

namespace App\Http\Controllers;

use App\Models\Estancia;
use App\Models\NotaAsignatura;
use App\Models\NotaCuaderno;
use App\Services\CalcularNotasCompetenciasTecnicas;
use App\Services\CalcularNotasCompetenciasTransversales;

class NotasController extends Controller {
    public function obtenerNotasTecnicas($alumnoId, CalcularNotasCompetenciasTecnicas $calcularNotas) {
        $notas = $calcularNotas->calcularNotasTecnicas($alumnoId);

        return response()->json([
            'alumno_id' => $alumnoId,
            'notas_competenciasTec' => array_values($notas),
        ]);
    }

    public function obtenerNotasTransversales($alumnoId, CalcularNotasCompetenciasTransversales $calcularNotas) {
        $notas = $calcularNotas->calcularNotasTransversales($alumnoId);

        return response()->json([
            'estancia_id' => $notas['estancia_id'],
            'nota_media' => $notas['nota_media'],
        ]);
    }

    public function obtenerNotasEgibide($alumnoId) {
        $notas = NotaAsignatura::where('alumno_id', $alumnoId)->get(["nota", "asignatura_id"]);

        return response()->json($notas);
    }

    public function obtenerNotaCuadernoByAlumno($alumnoId) {
        $estancia = Estancia::where('alumno_id', $alumnoId)->firstOrFail();

        $notaCuaderno = NotaCuaderno::whereHas('cuadernoPracticas', function ($query) use ($estancia) {
            $query->where('estancia_id', $estancia->id);
        })->firstOrFail('nota');

        return response()->json($notaCuaderno);
    }
}

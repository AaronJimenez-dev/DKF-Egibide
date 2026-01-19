<?php

namespace App\Http\Controllers;

use App\Models\CompetenciaTec;
use App\Models\CompetenciaTransversal;
use App\Models\Estancia;
use App\Models\NotaCompetenciaTec;
use Illuminate\Http\Request;

class CompetenciasController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $competenciasTec = CompetenciaTec::all()->map(function ($c) {
            return [
                'id' => $c->id,
                'descripcion' => $c->descripcion,
                'tipo' => 'TECNICA',
            ];
        });

        $competenciasTrans = CompetenciaTransversal::all()->map(function ($c) {
            return [
                'id' => $c->id,
                'descripcion' => $c->descripcion,
                'tipo' => 'TRANSVERSAL',
            ];
        });

        return response()->json(
            $competenciasTec
                ->merge($competenciasTrans)
                ->values()
        );
    }

    public function getCompetenciasTecnicasByAlumno($alumno_id) {
        $estancia = Estancia::where('alumno_id', $alumno_id)->firstOrFail();

        $competenciasTec = CompetenciaTec::whereHas('ciclo', function ($query) use ($estancia) {
            $query->where('ciclo_id', $estancia->curso->ciclo_id);
        })->get();

        return response()->json($competenciasTec);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeTecnica(Request $request) {
        $validated = $request->validate([
            'ciclo_id' => ['required'],
            'descripcion' => ['required']
        ]);

        CompetenciaTec::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Competencia técnica agregada correctamente'
        ], 201);
    }

    public function storeTransversal(Request $request) {
        $validated = $request->validate([
            'familia_profesional_id' => ['required'],
            'descripcion' => ['required']
        ]);

        CompetenciaTransversal::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Competencia transversal agregada correctamente'
        ], 201);
    }

    public function storeCompetenciasTecnicasAsignadas(Request $request) {
        $validated = $request->validate([
            'alumno_id' => ['required', 'integer'],
            'competencias' => ['required', 'array'],
            'competencias.*' => ['integer']
        ]);

        $alumno_id = $validated['alumno_id'];

        $estancia = Estancia::where('alumno_id', $alumno_id)->firstOrFail();

        foreach ($validated['competencias'] as $compenciaId) {
            NotaCompetenciaTec::create([
                'estancia_id' => $estancia->id,
                'competencia_tec_id' => $compenciaId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Competencias técnicas asignadas correctamente'
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CompetenciaTec;
use App\Models\CompetenciaTransversal;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
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

    /**
     * Display the specified resource.
     */
    public function show(CompetenciaTec $competencias) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompetenciaTec $competencias) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetenciaTec $competencias) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetenciaTec $competencias) {
        //
    }
}

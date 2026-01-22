<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use Database\Seeders\SeguimientosSeeder;
use Illuminate\Http\Request;

class SeguimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Seguimiento::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Seguimiento $seguimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seguimiento $seguimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seguimiento $seguimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seguimiento $seguimiento)
    {
        //
    }

    public function seguimientosAlumno($alumno_Id)
    {
        $seguimientos = Seguimiento::with('estancia')
            ->whereHas('estancia', function ($q) use ($alumno_Id) {
                $q->where('alumno_id', $alumno_Id);
            })
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($seguimientos);
    }

    public function nuevoSeguimiento(Request $request)
    {

    }
}

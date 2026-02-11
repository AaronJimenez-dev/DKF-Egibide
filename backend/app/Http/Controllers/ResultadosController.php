<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResultadoAprendizaje;
class ResultadosController extends Controller
{
    public function index(){
        $resultados = ResultadoAprendizaje::all();
        return $resultados;
    }
    public function store(Request $request){
        $validado = $request->validate([
            'descripcion' => ['required'],
            'asignatura_id' =>['required']
        ]);
        $resultado = ResultadoAprendizaje::create([
            'descripcion' => $validado['descripcion'],
            'asignatura_id' => $validado['asignatura_id'],
        ]); 
    }
}

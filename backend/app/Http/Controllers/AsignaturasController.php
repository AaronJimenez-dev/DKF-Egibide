<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignatura;
class AsignaturasController extends Controller
{
    public function index(){
        $asignaturas = Asignatura::all();
        return $asignaturas;        
    }
}

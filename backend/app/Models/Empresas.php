<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresas extends Model {
    protected $fillable = [
        'nombre',
        'cif',
        'telefono',
        'email',
        'direccion'
    ];

    /**
     * Get all instructores for this empresa
     */
    public function instructores(): HasMany {
        return $this->hasMany(TutorEmpresa::class);
    }

    /**
     * Get all estancias for this empresa
     */
    public function estancias(): HasMany {
        return $this->hasMany(Estancia::class);
    }
}

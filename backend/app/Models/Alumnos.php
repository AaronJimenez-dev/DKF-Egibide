<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumnos extends Model {
    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'ciudad',
        'user_id'
    ];

    /**
     * Get the user that owns this alumno
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all estancias for this alumno
     */
    public function estancias(): HasMany {
        return $this->hasMany(Estancia::class);
    }

    /**
     * Get all notas asignatura for this alumno
     */
    public function notasAsignatura(): HasMany {
        return $this->hasMany(NotaAsignatura::class);
    }
}

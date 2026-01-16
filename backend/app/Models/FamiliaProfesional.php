<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FamiliaProfesional extends Model {
    protected $fillable = [
        'nombre',
        'codigo_familia',
        'centro_id'
    ];

    protected $table = 'familias_profesionales';

    /**
     * Get the centro that owns this familia profesional
     */
    public function centro(): BelongsTo {
        return $this->belongsTo(Centro::class);
    }

    /**
     * Get all ciclos for this familia profesional
     */
    public function ciclos(): HasMany {
        return $this->hasMany(Ciclos::class);
    }

    /**
     * Get all competencias transversales for this familia profesional
     */
    public function competenciasTransversales(): HasMany {
        return $this->hasMany(CompetenciaTransversal::class);
    }
}

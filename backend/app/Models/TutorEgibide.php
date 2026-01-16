<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TutorEgibide extends Model {
    protected $table = 'tutores';

    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'ciudad',
        'user_id'
    ];

    /**
     * Get the user that owns this tutor
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all estancias for this tutor
     */
    public function estancias(): HasMany {
        return $this->hasMany(Estancia::class, 'tutor_id');
    }
}

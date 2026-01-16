<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaAsignatura extends Model {
  protected $table = 'notas_asignatura';

  protected $fillable = [
    'nota',
    'anio',
    'alumno_id',
    'asignatura_id',
  ];

  protected $casts = [
    'nota' => 'decimal:2',
  ];

  /**
   * Get the alumno that owns this nota
   */
  public function alumno(): BelongsTo {
    return $this->belongsTo(Alumnos::class);
  }

  /**
   * Get the asignatura that owns this nota
   */
  public function asignatura(): BelongsTo {
    return $this->belongsTo(Asignatura::class);
  }
}

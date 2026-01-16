<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HorarioTramo extends Model {
  protected $table = 'horarios_tramo';

  protected $fillable = [
    'hora_inicio',
    'hora_fin',
    'horario_dia_id',
  ];

  protected $casts = [
    'hora_inicio' => 'time',
    'hora_fin' => 'time',
  ];

  /**
   * Get the horario dia that owns this horario tramo
   */
  public function horarioDia(): BelongsTo {
    return $this->belongsTo(HorarioDia::class);
  }
}

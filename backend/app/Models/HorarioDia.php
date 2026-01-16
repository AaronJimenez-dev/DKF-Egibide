<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HorarioDia extends Model {
  protected $table = 'horarios_dia';

  protected $fillable = [
    'dia_semana',
    'estancia_id',
  ];

  /**
   * Get the estancia that owns this horario
   */
  public function estancia(): BelongsTo {
    return $this->belongsTo(Estancia::class);
  }

  /**
   * Get all horarios tramo for this dia
   */
  public function horariosTramo(): HasMany {
    return $this->hasMany(HorarioTramo::class);
  }
}

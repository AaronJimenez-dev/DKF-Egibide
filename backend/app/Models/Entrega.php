<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model {
  protected $table = 'entregas';

  protected $fillable = [
    'archivo',
    'fecha',
    'cuaderno_practicas_id',
  ];

  protected $casts = [
    'fecha' => 'date',
  ];

  /**
   * Get the cuaderno practicas that owns this entrega
   */
  public function cuadernoPracticas(): BelongsTo {
    return $this->belongsTo(CuadernoPracticas::class);
  }
}

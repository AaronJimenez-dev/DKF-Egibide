<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuadernoPracticas extends Model {
  protected $table = 'cuadernos_practicas';

  protected $fillable = [
    'archivo',
    'archivo_vacio',
    'estancia_id',
  ];

  /**
   * Get the estancia that owns this cuaderno
   */
  public function estancia(): BelongsTo {
    return $this->belongsTo(Estancia::class);
  }

  /**
   * Get all entregas for this cuaderno
   */
  public function entregas(): HasMany {
    return $this->hasMany(Entrega::class);
  }

  /**
   * Get the nota for this cuaderno
   */
  public function nota() {
    return $this->hasOne(NotaCuaderno::class);
  }
}

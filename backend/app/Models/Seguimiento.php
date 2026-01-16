<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model {
  protected $table = 'seguimientos';

  protected $fillable = [
    'accion',
    'fecha',
    'descripcion',
    'via',
    'estancia_id',
  ];

  protected $casts = [
    'fecha' => 'date',
  ];

  /**
   * Get the estancia that owns this seguimiento
   */
  public function estancia(): BelongsTo {
    return $this->belongsTo(Estancia::class);
  }
}

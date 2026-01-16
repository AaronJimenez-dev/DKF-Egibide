<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model {
  protected $fillable = [
    'numero',
    'ciclo_id',
  ];

  /**
   * Get the ciclo that owns this curso
   */
  public function ciclo(): BelongsTo {
    return $this->belongsTo(Ciclos::class);
  }

  /**
   * Get all estancias for this curso
   */
  public function estancias(): HasMany {
    return $this->hasMany(Estancia::class);
  }
}

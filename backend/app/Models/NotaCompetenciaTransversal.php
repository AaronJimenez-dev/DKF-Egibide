<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaCompetenciaTransversal extends Model {
  protected $table = 'notas_competencia_trans';

  protected $fillable = [
    'nota',
    'competencia_trans_id',
    'estancia_id',
  ];

  protected $casts = [
    'nota' => 'decimal:2',
  ];

  /**
   * Get the competencia transversal that owns this nota
   */
  public function competenciaTransversal(): BelongsTo {
    return $this->belongsTo(CompetenciaTransversal::class);
  }

  /**
   * Get the estancia that owns this nota
   */
  public function estancia(): BelongsTo {
    return $this->belongsTo(Estancia::class);
  }
}

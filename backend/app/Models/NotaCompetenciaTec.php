<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaCompetenciaTec extends Model {
  protected $table = 'notas_competencia_tec';

  protected $fillable = [
    'nota',
    'competencia_tec_id',
    'estancia_id',
  ];

  protected $casts = [
    'nota' => 'decimal:2',
  ];

  /**
   * Get the competencia tec that owns this nota
   */
  public function competenciaTec(): BelongsTo {
    return $this->belongsTo(CompetenciaTec::class);
  }

  /**
   * Get the estancia that owns this nota
   */
  public function estancia(): BelongsTo {
    return $this->belongsTo(Estancia::class);
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResultadoAprendizaje extends Model {
  protected $table = 'resultados_aprendizaje';

  protected $fillable = [
    'descripcion',
    'asignatura_id',
  ];

  /**
   * Get the asignatura that owns this resultado aprendizaje
   */
  public function asignatura(): BelongsTo {
    return $this->belongsTo(Asignatura::class);
  }

  /**
   * Get all competencias tec that have this resultado aprendizaje
   */
  public function competenciasTec(): BelongsToMany {
    return $this->belongsToMany(
      CompetenciaTec::class,
      'competencia_tec_ra',
      'resultado_aprendizaje_id',
      'competencia_tec_id'
    );
  }
}

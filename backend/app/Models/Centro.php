<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Centro extends Model {
  protected $fillable = [
    'nombre',
    'calle',
  ];

  /**
   * Get all familia profesional for the centro
   */
  public function familiasProfesionales(): HasMany {
    return $this->hasMany(FamiliaProfesional::class);
  }
}

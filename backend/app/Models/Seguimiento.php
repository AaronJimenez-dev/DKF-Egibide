<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model
{
    use HasFactory;

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

    public function estancia(): BelongsTo
    {
        return $this->belongsTo(Estancia::class, 'estancia_id');
    }
}

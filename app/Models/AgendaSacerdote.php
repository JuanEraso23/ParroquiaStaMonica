<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaSacerdote extends Model
{
    use HasFactory;

    protected $table = 'agendas_sacerdotes';

    protected $fillable = [
        'sacerdote_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'abierta_por',
        'abierta_en',
        'fuera_de_plazo',
        'motivo_apertura_extraordinaria',
        'cerrada_por',
        'cerrada_en',
    ];

    protected $casts = [
        'fecha' => 'date',
        'abierta_en' => 'datetime',
        'cerrada_en' => 'datetime',
        'fuera_de_plazo' => 'boolean',
    ];

    public function sacerdote(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sacerdote_id');
    }

    public function usuarioApertura(): BelongsTo
    {
        return $this->belongsTo(User::class, 'abierta_por');
    }

    public function usuarioCierre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cerrada_por');
    }

    public function estaAbierta(): bool
    {
        return $this->estado === 'abierta';
    }

    public function estaCerrada(): bool
    {
        return $this->estado === 'cerrada';
    }

    public function fueAbiertaFueraDePlazo(): bool
    {
        return (bool) $this->fuera_de_plazo;
    }
}

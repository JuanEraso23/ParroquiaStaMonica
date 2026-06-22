<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'feligres_id',
        'sacerdote_id',
        'fecha',
        'hora',
        'duracion_minutos',
        'hora_fin',
        'tipo',
        'descripcion',
        'estado',
        'notas_internas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function feligres()
    {
        return $this->belongsTo(User::class, 'feligres_id');
    }

    public function sacerdote()
    {
        return $this->belongsTo(User::class, 'sacerdote_id');
    }

    // Accesores
    public function getTipoTextoAttribute()
    {
        $tipos = [
            'confesion' => 'Confesión',
            'bautismo' => 'Bautismo',
            'matrimonio' => 'Matrimonio',
            'orientacion' => 'Orientación',
        ];

        return $tipos[$this->tipo] ?? ucfirst($this->tipo);
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'confirmada' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            'completada' => 'bg-blue-100 text-blue-800',
        ];

        return $badges[$this->estado] ?? 'bg-gray-100 text-gray-800';
    }

    public function getHoraFormateadaAttribute()
    {
        return \Carbon\Carbon::parse($this->hora)->format('g:i A');
    }

    public function getHoraFinFormateadaAttribute()
    {
        return $this->hora_fin
            ? \Carbon\Carbon::parse($this->hora_fin)->format('g:i A')
            : null;
    }

    public function getHorarioCompletoAttribute()
    {
        if (!$this->hora_fin) {
            return $this->hora_formateada;
        }

        return $this->hora_formateada . ' - ' . $this->hora_fin_formateada;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intencion extends Model
{
    use HasFactory;

    protected $table = 'intenciones';

    protected $fillable = [
        'feligres_id',
        'sacerdote_id',
        'titulo',
        'descripcion',
        'fecha',
        'nombre_difunto',
        'fecha_misa',
        'estado',
        'respuesta',
        'notas_internas',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_misa' => 'date',
    ];

    public function feligres()
    {
        return $this->belongsTo(User::class, 'feligres_id');
    }

    public function sacerdote()
    {
        return $this->belongsTo(User::class, 'sacerdote_id');
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'confirmada' => 'bg-green-100 text-green-800',
            'realizada' => 'bg-blue-100 text-blue-800',
            'cancelada' => 'bg-red-100 text-red-800',
        ];
        return $badges[$this->estado] ?? 'bg-gray-100 text-gray-800';
    }
}
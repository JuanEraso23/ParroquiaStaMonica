<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaPeticion extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function peticiones()
    {
        return $this->hasMany(Peticion::class);
    }
}
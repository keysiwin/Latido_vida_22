<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = 'persons';  // Especifica el nombre de la tabla

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'celular',
        'estado',
    ];
    public function latestLocation()
    {
        return $this->hasOne(Location::class)->latestOfMany();
    }
}

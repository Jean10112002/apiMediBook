<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosPersonale extends Model
{
    use HasFactory;

    protected $table="datos_personales";
    protected $fillable=[
        "telefono","edad","fecha","ci"
    ];
    public $timestamps=false;

}

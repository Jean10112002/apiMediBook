<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;
    protected $table="mensajes";
    protected $fillable=[
        "contenido",
        "usuario_enviado",
        "usuario_recibido",
    ];
    public $timestamps=false;
}

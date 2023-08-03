<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resenia extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="resenias";
    protected $fillable=[
        "calificacion",
        "comentario_io",
        "cita_id",
    ];
}

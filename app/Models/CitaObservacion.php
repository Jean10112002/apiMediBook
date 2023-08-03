<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaObservacion extends Model
{
    use HasFactory;
    protected $table="cita_observacions";
    protected $fillable=[
        "contenido",
        "cita_id",
    ];
    public $timestamps=false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    protected $table="citas";
    protected $fillable=[
        "fecha",
        "titulo",
        "hora_inicio",
        "hora_fin",
        "medico_id",
        "paciente_id",
        "estado_cita_id",
    ];
    public $timestamps=false;
}

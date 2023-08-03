<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntecedentesMedico extends Model
{
    use HasFactory;
    protected $table="antecedentes_medicos";
    protected $fillable=[
        "condicion_medica",
        "alergias",
        "cirugias_previas",
        "tipo_sangre",
        "otros_datos",
        "paciente_id",
    ];
    public $timestamps=false;
}

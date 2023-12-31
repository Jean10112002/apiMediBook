<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function Paciente():BelongsTo{
        return $this->belongsTo(Paciente::class,'paciente_id');
    }
}

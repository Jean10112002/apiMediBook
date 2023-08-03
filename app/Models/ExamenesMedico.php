<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenesMedico extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="examenes_medicos";
    protected $fillable=[
        "nombre","fecha","resultado","paciente_id"
    ];
}

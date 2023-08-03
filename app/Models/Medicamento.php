<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    use HasFactory;
    protected $table="medicamentos";
    protected $fillable=[
        "nombre",
        "dosis",
        "lapso",
        "duracion",
        "paciente_id",
    ];
    public $timestamps=false;
}

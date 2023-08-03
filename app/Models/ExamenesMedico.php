<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenesMedico extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="examenes_medicos";
    protected $fillable=[
        "nombre","fecha","resultado","paciente_id"
    ];
    public function Paciente():BelongsTo{
        return $this->belongsTo(Paciente::class,'paciente_id');
    }
}

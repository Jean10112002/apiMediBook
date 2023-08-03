<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function Paciente():BelongsTo{
        return $this->belongsTo(Paciente::class,'paciente_id');
    }
}

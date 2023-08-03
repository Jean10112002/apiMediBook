<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacuna extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="vacunas";
    protected $fillable=[
        "nombre","fecha","paciente_id"
    ];
    public function Paciente():BelongsTo{
        return $this->belongsTo(Paciente::class,'paciente_id');
    }
}

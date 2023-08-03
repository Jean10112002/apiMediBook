<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="pagos";
    protected $fillable=[
        "cantidad",
        "medico_id",
        "paciente_id",
        "cita_id",
    ];
}

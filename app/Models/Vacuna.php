<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="vacunas";
    protected $fillable=[
        "nombre","fecha","paciente_id"
    ];
}

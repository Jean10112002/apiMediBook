<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;
    protected $table="medicos";
    protected $fillable=[
        "user_id",
        "especialidad_id",
    ];
    public $timestamps=false;
}

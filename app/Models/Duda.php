<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duda extends Model
{
    use HasFactory;
    protected $table="dudas";
    protected $fillable=[
        "fecha",
        "contenido",
        "paciente_id",
    ];
    public $timestamps=false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    use HasFactory;
    protected $table="especialidades";
    protected $fillable=[
        "nombre"
    ];
    public $timestamps=false;

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Especialidade extends Model
{
    use HasFactory;
    protected $table="especialidades";
    protected $fillable=[
        "nombre"
    ];
    public $timestamps=false;
    public function Medico():HasMany{
        return $this->HasMany(Medico::class,'especialidad_id');
    }
}

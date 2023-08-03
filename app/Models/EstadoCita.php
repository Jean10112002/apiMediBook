<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoCita extends Model
{
    use HasFactory;
    protected $table="estado_citas";
    protected $fillable=[
        "nombre"
    ];
    public $timestamps=false;
    public function Cita():HasMany{
        return $this->HasMany(Cita::class,'estado_cita_id');
    }

}

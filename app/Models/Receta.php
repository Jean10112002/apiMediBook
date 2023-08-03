<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receta extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="recetas";
    protected $fillable=[
        "nombre_medicamento",
        "cantidad",
        "lapso_tiempo",
        "cita_id",
    ];

    public function Cita():BelongsTo{
        return $this->BelongsTo(Cita::class,'cita_id');
    }
}

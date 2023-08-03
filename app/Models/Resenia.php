<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resenia extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="resenias";
    protected $fillable=[
        "calificacion",
        "comentario_io",
        "cita_id",
    ];
    public function Cita():BelongsTo{
        return $this->BelongsTo(Cita::class,'cita_id');
    }
    public function Comentario():BelongsTo{
        return $this->BelongsTo(Comentario::class,'comentario_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comentario extends Model
{
    use HasFactory;
    protected $table="comentarios";
    protected $fillable=[
        "nombre"
    ];
    public $timestamps=false;
    public function Reseña():HasMany{
        return $this->HasMany(Resenia::class,'comentario_id');
    }
}

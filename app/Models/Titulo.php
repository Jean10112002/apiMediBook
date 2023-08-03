<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Titulo extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="titulos";
    protected $fillable=[
        "nombre",
        "fecha",
        "medico_id",
    ];
    public function Medico():BelongsTo{
        return $this->BelongsTo(Medico::class,'medico_id');
    }
}

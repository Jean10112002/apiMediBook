<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    use HasFactory;
    protected $table="horarios";
    protected $fillable=[
        "dia",
        "hora_inicio",
        "hora_fin",
        "medico_id",
    ];
    public $timestamps=false;
    public function Medico():BelongsTo{
        return $this->belongsTo(Medico::class,'medico_id');
    }

}

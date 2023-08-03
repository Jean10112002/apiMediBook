<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitaObservacion extends Model
{
    use HasFactory;
    protected $table="cita_observacions";
    protected $fillable=[
        "contenido",
        "cita_id",
    ];
    public $timestamps=false;
    public function Cita():BelongsTo{
        return $this->belongsTo(Cita::class,'cita_id');
    }

}

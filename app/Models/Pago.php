<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="pagos";
    protected $fillable=[
        "cantidad",
        "medico_id",
        "paciente_id",
        "cita_id",
    ];
    public function Medico():BelongsTo{
        return $this->BelongsTo(Medico::class,'medico_id');
    }
    public function Paciente():BelongsTo{
        return $this->BelongsTo(Paciente::class,'paciente_id');
    }
    public function Cita():BelongsTo{
        return $this->BelongsTo(Cita::class,'cita_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cita extends Model
{
    use HasFactory;
    protected $table="citas";
    protected $fillable=[
        "fecha",
        "titulo",
        "hora_inicio",
        "hora_fin",
        "medico_id",
        "paciente_id",
        "estado_cita_id",
    ];
    public $timestamps=false;
    public function Paciente():BelongsTo{
        return $this->belongsTo(Paciente::class,'paciente_id');
    }
    public function Medico():BelongsTo{
        return $this->belongsTo(Medico::class,'medico_id');
    }
    public function EstadoCita():BelongsTo{
        return $this->belongsTo(EstadoCita::class,'estado_cita_id');
    }

    public function Receta():HasMany{
        return $this->HasMany(Receta::class,'cita_id');
    }
    public function Resenia():HasMany{
        return $this->HasMany(Resenia::class,'cita_id');
    }
    public function CitaObservacion():HasMany{
        return $this->HasMany(CitaObservacion::class,'cita_id');
    }
    public function Pago():HasMany{
        return $this->HasMany(Pago::class,'cita_id');
    }
}

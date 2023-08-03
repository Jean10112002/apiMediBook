<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medico extends Model
{
    use HasFactory;
    protected $table="medicos";
    protected $fillable=[
        "user_id",
        "especialidad_id",
    ];
    public $timestamps=false;

    public function Usuario():BelongsTo{
        return $this->belongsTo(User::class,'user_id');
    }
    public function Especialidad():BelongsTo{
        return $this->belongsTo(Especialidade::class,'especialidad_id');
    }

    public function Cita():HasMany{
        return $this->HasMany(Cita::class,'medico_id');
    }
    public function Horario():HasMany{
        return $this->HasMany(Horario::class,'medico_id');
    }
    public function Titulo():HasMany{
        return $this->HasMany(Titulo::class,'medico_id');
    }
    public function Pago():HasMany{
        return $this->HasMany(Pago::class,'medico_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;
    protected $table="pacientes";
    protected $fillable=[
        "user_id",
    ];
    public $timestamps=false;
    public function Usuario():BelongsTo{
        return $this->belongsTo(User::class,'user_id');
    }

    public function Cita():HasMany{
        return $this->HasMany(Cita::class,'paciente_id');
    }
    public function Duda():HasMany{
        return $this->HasMany(Duda::class,'paciente_id');
    }
    public function AntecedentesMedico():HasMany{
        return $this->HasMany(AntecedentesMedico::class,'paciente_id');
    }
    public function Medicamento():HasMany{
        return $this->HasMany(Medicamento::class,'paciente_id');
    }
    public function Vacuna():HasMany{
        return $this->HasMany(Vacuna::class,'paciente_id');
    }
    public function ExamenesMedico():HasMany{
        return $this->HasMany(ExamenesMedico::class,'paciente_id');
    }
    public function Pago():HasMany{
        return $this->HasMany(Pago::class,'paciente_id');
    }

}

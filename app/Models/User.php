<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        "rol_id",
        "datos_personales_id",
        "ubicacion_id"
    ];
    public $timestamps=false;
    protected $hidden = [
        'password',
    ];

    public function Rol():BelongsTo{
        return $this->BelongsTo(Role::class,'rol_id');
    }
    public function DatosPersonale():BelongsTo{
        return $this->BelongsTo(DatosPersonale::class,'datos_personales_id');
    }
    public function Ubicacion():BelongsTo{
        return $this->BelongsTo(Ubicacion::class,'ubicacion_id');
    }

    public function Medico():HasMany{
        return $this->HasMany(Medico::class,'medico_id');
    }
    public function Paciente():HasMany{
        return $this->HasMany(Paciente::class,'paciente_id');
    }


}

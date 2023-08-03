<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DatosPersonale extends Model
{
    use HasFactory;

    protected $table="datos_personales";
    protected $fillable=[
        "telefono","edad","fecha","ci"
    ];
    public $timestamps=false;
    public function Usuario():HasMany{
        return $this->HasMany(User::class,'datos_personales_id');
    }
}

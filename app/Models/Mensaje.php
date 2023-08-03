<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensaje extends Model
{
    use HasFactory;
    protected $table="mensajes";
    protected $fillable=[
        "contenido",
        "usuario_enviado_id",
        "usuario_recibido_id",
    ];
    public $timestamps=false;
    public function UsuarioEnviado():BelongsTo{
        return $this->BelongsTo(User::class,'usuario_enviado_id');
    }
    public function UsuarioRecibido():BelongsTo{
        return $this->BelongsTo(User::class,'usuario_recibido_id');
    }
}

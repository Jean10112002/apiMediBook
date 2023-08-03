<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Duda extends Model
{
    use HasFactory;
    protected $table="dudas";
    protected $fillable=[
        "fecha",
        "contenido",
        "paciente_id",
    ];
    public $timestamps=false;
    public function Paciente():BelongsTo{
        return $this->BelongsTo(Paciente::class,'paciente_id');
    }
    public function ReplyDuda():HasMany{
        return $this->HasMany(ReplyDuda::class,'duda_id');
    }

}

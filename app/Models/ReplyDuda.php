<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyDuda extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="reply_dudas";
    protected $fillable=[
        "fecha",
        "contenido",
        "user_id",
        "cita_id",
    ];
}

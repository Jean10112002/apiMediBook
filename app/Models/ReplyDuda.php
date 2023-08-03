<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReplyDuda extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="reply_dudas";
    protected $fillable=[
        "fecha",
        "contenido",
        "user_id",
        "duda_id",
    ];
    public function Duda():BelongsTo{
        return $this->BelongsTo(Duda::class,'duda_id');
    }
    public function Usuario():BelongsTo{
        return $this->BelongsTo(User::class,'user_id');
    }
}

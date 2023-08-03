<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="ubicacions";
    protected $fillable=[
        "canton","provincia"
    ];
    public function Usuario():HasMany{
        return $this->HasMany(User::class,'ubicacion_id');
    }
}

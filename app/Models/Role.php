<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="roles";
    protected $fillable=[
        "nombre"
    ];
    public function Usuario():HasMany{
        return $this->HasMany(User::class,'rol_id');
    }
}

<?php

namespace Database\Seeders;

use App\Models\DatosPersonale;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DatosPersonale::create([
            "telefono"=>"0123456789",
            "edad"=>"20",
            "fecha"=>"2022-11-10",
            "ci"=>"1313626440"
        ]);
        Ubicacion::create([
            "canton"=>"Chone","provincia"=>"Manabí"
        ]);
        User::create([
            'nombre'=>"Admin",
            'apellido'=>"Admin",
            'email'=>"admin@hotmail.com",
            'password'=>bcrypt('admin123'),
            "rol_id"=>1,
            "datos_personales_id"=>1,
            "ubicacion_id"=>1
        ]);
    }
}

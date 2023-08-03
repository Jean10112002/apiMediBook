<?php

namespace Database\Seeders;

use App\Models\Comentario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComentarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comentarios=array('Buena atención',"Excelente atención","Mala atención","Pésima atención");
        foreach ($comentarios as $c){
            Comentario::create([
                "nombre"=>$c
            ]);
        }
    }
}

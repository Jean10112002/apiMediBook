<?php

namespace Database\Seeders;

use App\Models\EstadoCita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoCitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estadoCita=array('Pendiente','Cancelado','Exitoso');
        foreach ($estadoCita as $e){
            EstadoCita::create([
                "nombre"=>$e
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Especialidade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EspecialidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidadesMedicina = [
            'Cardiología',
            'Dermatología',
            'Endocrinología',
            'Gastroenterología',
            'Hematología',
            'Infectología',
            'Medicina Familiar',
            'Nefrología',
            'Neurología',
            'Oftalmología',
            'Oncología',
            'Pediatría',
            'Psiquiatría',
            'Reumatología',
            'Traumatología',
            'Urología',
        ];
        foreach ($especialidadesMedicina as $especialidad){
            Especialidade::create([
                "nombre"=>$especialidad
            ]);
        }
    }
}

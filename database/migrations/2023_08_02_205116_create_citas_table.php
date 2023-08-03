<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('titulo');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->boolean('estado')->default(1);
            $table->foreignId('medico_id')
            ->constrained('medicos')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('paciente_id')
            ->constrained('pacientes')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('estado_cita_id')
            ->constrained('estado_citas')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};

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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->double('cantidad');
            $table->foreignId('cita_id')
            ->constrained('citas')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('medico_id')
            ->constrained('medicos')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('paciente_id')
            ->constrained('pacientes')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

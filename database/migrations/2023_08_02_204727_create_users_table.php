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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('rol_id')
            ->constrained('roles')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('datos_personales_id')
            ->constrained('datos_personales')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('ubicacion_id')
            ->constrained('ubicacions')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

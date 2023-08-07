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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('contenido');
            $table->dateTime('fecha');
            $table->foreignId('usuario_enviado_id')
            ->constrained('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('usuario_recibido_id')
            ->constrained('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};

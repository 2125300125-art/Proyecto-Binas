<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administradores', function (Blueprint $table) {

            $table->id();
            $table->string('nombre');

            $table->string('apellidos');
            $table->string('correo')->unique();
            $table->string('usuario')->unique();
            $table->string('contraseña');
            $table->string('imagen')->nullable();
            $table->foreignId('rol_id')
                ->constrained('roles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};
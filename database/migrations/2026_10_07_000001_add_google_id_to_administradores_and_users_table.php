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
        // Agregar a la tabla administradores si existe
        if (Schema::hasTable('administradores')) {
            Schema::table('administradores', function (Blueprint $table) {
                if (!Schema::hasColumn('administradores', 'google_id')) {
                    $table->string('google_id')->nullable()->after('correo');
                }
            });
        }

        // Agregar a la tabla users si existe
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'google_id')) {
                    $table->string('google_id')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('google_id');
                }
                // Permitir contraseñas nulas para usuarios autenticados únicamente con Google
                if (Schema::hasColumn('users', 'password')) {
                    $table->string('password')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('administradores') && Schema::hasColumn('administradores', 'google_id')) {
            Schema::table('administradores', function (Blueprint $table) {
                $table->dropColumn('google_id');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'google_id')) {
                    $table->dropColumn('google_id');
                }
                if (Schema::hasColumn('users', 'avatar')) {
                    $table->dropColumn('avatar');
                }
            });
        }
    }
};


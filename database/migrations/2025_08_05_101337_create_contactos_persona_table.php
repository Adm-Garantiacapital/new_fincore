<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contactos_persona', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->string('dni')->nullable();
            $table->string('nombre');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('cargo')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('pagina_web')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contactos_persona');
    }
};

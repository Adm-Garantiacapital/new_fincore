<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void{
        Schema::create('aceptantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->constrained('personas')
                ->onDelete('cascade');

            $table->string('ruc', 11)->unique();
            $table->string('razon_social');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_sentinel')->nullable();
            $table->string('cliente_situacion')->nullable();
            $table->decimal('endeud_bancario', 15, 2)->nullable();
            $table->boolean('prorestos')->default(false);
            $table->integer('protestos')->default(0);
            $table->string('rl_nombre')->nullable();
            $table->string('situacion_sf')->nullable();
            $table->integer('edad')->nullable();
            $table->string('top')->nullable();
            $table->decimal('ventas_aprox_pagina_web', 15, 2)->nullable();
            $table->string('entidades_apefact')->nullable();
            $table->decimal('endeud_apfec', 15, 2)->nullable();
            $table->decimal('endeud_prom', 15, 2)->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aceptantes');
    }
};

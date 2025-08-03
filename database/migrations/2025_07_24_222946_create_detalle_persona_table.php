<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_persona', function (Blueprint $table) {
            $table->id();

            // Relación con persona
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');

            // Datos
            $table->string('anio')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->date('fecha_inscripcion')->nullable();
            $table->date('fecha_inicio_actividades')->nullable();
            $table->string('estado_contribuyente')->nullable();
            $table->string('condicion_contribuyente')->nullable();
            $table->string('domicilio_fiscal')->nullable();
            $table->string('actividad_comercio_exterior')->nullable();
            $table->string('actividad_economica')->nullable();

            // Datos financieros
            $table->decimal('ingresos_netos', 15, 2)->nullable();
            $table->decimal('otros_ingresos', 15, 2)->nullable();
            $table->decimal('total_activos', 15, 2)->nullable();
            $table->decimal('total_cuentas_por_pagar', 15, 2)->nullable();
            $table->decimal('total_pasivo', 15, 2)->nullable();
            $table->decimal('total_patrimonio', 15, 2)->nullable();
            $table->decimal('capital_social', 15, 2)->nullable();
            $table->decimal('resultado_bruto', 15, 2)->nullable();
            $table->decimal('resultado_antes_imp', 15, 2)->nullable();
            $table->decimal('importe_pagado', 15, 2)->nullable();
            $table->decimal('ingreso_12_meses', 15, 2)->nullable();
            $table->decimal('ingreso_6_meses', 15, 2)->nullable();
            $table->decimal('promedio_ingreso_12_meses', 15, 2)->nullable();
            $table->decimal('promedio_ingreso_6_meses', 15, 2)->nullable();

            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_persona');
    }
};

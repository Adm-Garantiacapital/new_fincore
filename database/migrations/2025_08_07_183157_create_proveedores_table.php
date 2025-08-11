<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('ruc')->unique();
            $table->string('razon_social')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->date('fecha_inicio_actividad')->nullable();
            $table->string('actividad_economica')->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('ventas_aproximadas', 15, 2)->nullable();
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('proveedores');
    }
};

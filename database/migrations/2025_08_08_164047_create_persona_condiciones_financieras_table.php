<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('persona_condiciones_financieras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');

            $table->decimal('linea_cliente', 15, 2)->nullable();
            $table->decimal('porcentaje_anticipo', 5, 2)->nullable();
            $table->decimal('monto_comision', 15, 2)->nullable();
            $table->decimal('tasa_tem', 5, 2)->nullable();
            $table->decimal('adelanto', 15, 2)->nullable();
            $table->decimal('linea_adelanto', 15, 2)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('persona_condiciones_financieras');
    }
};

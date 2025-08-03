<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();

            // Datos básicos
            $table->string('nombre')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->enum('tipo', ['proveedor', 'cliente']);

            // Documento
            $table->string('documento')->nullable();
            $table->enum('tipo_documento', ['dni', 'ruc', 'carnet_extranjeria']);

            // Contacto
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();

            // Datos empresa (si aplica)
            $table->string('razon_social')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->date('fecha_inicio_actividad')->nullable();
            $table->string('actividad_economica')->nullable();
            $table->decimal('ventas_aproximadas', 15, 2)->nullable();
            $table->string('pagina_web')->nullable();

            // Datos personales
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('estado_civil')->nullable();

            // Relaciones
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade')->unique();
            $table->foreignId('estado_id')->constrained('estados');

            // Descripción
            $table->text('descripcion')->nullable();

            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('personas');
    }
};

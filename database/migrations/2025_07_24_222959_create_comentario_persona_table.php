<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentario_persona', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');

            // Usuario que hizo el comentario (autor)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->text('comentario');

            // Auditoría (quién lo actualizó o eliminó)
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentario_persona');
    }
};

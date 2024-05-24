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
        Schema::create('livro_modulos', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('livro_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('modulo_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_modulos');
    }
};

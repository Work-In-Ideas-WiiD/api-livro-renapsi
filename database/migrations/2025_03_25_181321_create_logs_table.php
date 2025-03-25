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
        Schema::create('logs', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['download', 'online', 'modulo']);
            $table->string('model_nome')
                ->nullable()
                ->comment('nome do model, no formato Namespace/Model, por ex: App\Models\Conta\Conta, o uso é recomendado, porém é opcional');
            $table->uuid('model_id')
                ->nullable()
                ->comment('id do model da ação, o uso é recomendado, porém é opcional, sempre use junto com model_name');
            $table->string('ip');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};

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
        Schema::create('program_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('entity_type'); // Indicar el tipo de entidad: 'user', 'challenge', 'company'
            $table->unsignedBigInteger('entity_id'); // ID de la entidad participante
            $table->foreign('entity_id')->references('id')->on('users')->onDelete('cascade'); // Ajustar para otras entidades
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_participants');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vagas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->enum('tipo', ['carro', 'moto'])->default('carro');
            $table->enum('status', ['livre', 'ocupada'])->default('livre');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('vagas');
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaga_id')->constrained('vagas');
            $table->string('placa', 10);
            $table->dateTime('entrada');
            $table->dateTime('saida')->nullable();
            $table->decimal('valor', 8, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('registros');
    }
};
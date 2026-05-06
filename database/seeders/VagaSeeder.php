<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaga;

class VagaSeeder extends Seeder {
    public function run(): void {
        // 10 vagas de carro
        for ($i = 1; $i <= 10; $i++) {
            Vaga::create(['numero' => $i, 'tipo' => 'carro']);
        }
        // 5 vagas de moto
        for ($i = 11; $i <= 15; $i++) {
            Vaga::create(['numero' => $i, 'tipo' => 'moto']);
        }
    }
}
<?php
use App\Http\Controllers\EstacionamentoController;
use Illuminate\Support\Facades\Route;

// Retorna todas as vagas
Route::get('/vagas', [EstacionamentoController::class, 'vagasApi']);

// Reservar uma vaga
Route::post('/reservar', [EstacionamentoController::class, 'reservarApi']);
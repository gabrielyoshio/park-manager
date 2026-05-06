<?php
use App\Http\Controllers\EstacionamentoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Página pública
Route::get('/', [EstacionamentoController::class, 'index'])->name('vagas.public');

// Área do gestor (requer login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [EstacionamentoController::class, 'dashboard'])->name('dashboard');
    Route::post('/entrada', [EstacionamentoController::class, 'entrada'])->name('entrada');
    Route::post('/saida/{registro}', [EstacionamentoController::class, 'saida'])->name('saida');
    Route::get('/historico', [EstacionamentoController::class, 'historico'])->name('historico');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/reservar', [EstacionamentoController::class, 'reservar'])->name('reservar');
require __DIR__.'/auth.php';
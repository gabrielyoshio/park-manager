<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model {
    protected $fillable = ['vaga_id', 'placa', 'nome', 'entrada', 'saida', 'valor'];

    protected $casts = [
        'entrada' => 'datetime',
        'saida'   => 'datetime',
    ];

    public function vaga() {
        return $this->belongsTo(Vaga::class);
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaga extends Model {
    protected $fillable = ['numero', 'tipo', 'status'];

    public function registros() {
        return $this->hasMany(Registro::class);
    }

    public function registroAtivo() {
        return $this->hasOne(Registro::class)->whereNull('saida');
    }
}
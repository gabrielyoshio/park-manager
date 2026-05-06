<?php
namespace App\Http\Controllers;

use App\Models\Vaga;
use App\Models\Registro;
use Illuminate\Http\Request;

class EstacionamentoController extends Controller
{
    // Página pública
    public function index() {
        $vagas = Vaga::with('registroAtivo')->orderBy('numero')->get();
        return view('public.vagas', compact('vagas'));
    }

    // Dashboard do gestor
    public function dashboard() {
        $totalVagas     = Vaga::count();
        $vagasLivres    = Vaga::where('status', 'livre')->count();
        $vagasOcupadas  = Vaga::where('status', 'ocupada')->count();
        $receitaHoje    = Registro::whereDate('saida', today())->sum('valor');
        $registrosAtivos = Registro::with('vaga')->whereNull('saida')->get();

        return view('dashboard', compact(
            'totalVagas','vagasLivres','vagasOcupadas','receitaHoje','registrosAtivos'
        ));
    }

    // Registrar entrada
    public function entrada(Request $request) {
        $request->validate([
            'vaga_id' => 'required|exists:vagas,id',
            'placa'   => 'required|string|max:10',
        ]);

        $vaga = Vaga::findOrFail($request->vaga_id);

        if ($vaga->status === 'ocupada') {
            return back()->with('error', 'Essa vaga já está ocupada!');
        }

        Registro::create([
            'vaga_id' => $vaga->id,
            'placa'   => strtoupper($request->placa),
            'entrada' => now(),
        ]);

        $vaga->update(['status' => 'ocupada']);

        return back()->with('success', 'Entrada registrada com sucesso!');
    }

    // Registrar saída
public function saida(Registro $registro) {
    $saida = now();
    $horas = ceil($registro->entrada->diffInMinutes($saida) / 60); // arredonda pra cima
    $valorPorHora = $registro->vaga->tipo === 'carro' ? 15 : 10;
    $valor = max($horas * $valorPorHora, $valorPorHora); // mínimo 1 hora

    $registro->update([
        'saida' => $saida,
        'valor' => $valor,
    ]);

    $registro->vaga->update(['status' => 'livre']);

    return back()->with('success', 'Saída registrada! Tempo: ' . $horas . 'h — Valor: R$ ' . number_format($valor, 2, ',', '.'));
}

    // Histórico completo
    public function historico() {
        $registros = Registro::with('vaga')->latest()->paginate(20);
        return view('gestor.historico', compact('registros'));
    }
// Reserva pelo cliente
public function reservar(Request $request) {
    $request->validate([
        'vaga_id' => 'required|exists:vagas,id',
        'placa'   => 'required|string|max:10',
    ]);

    $vaga = Vaga::findOrFail($request->vaga_id);

    if ($vaga->status === 'ocupada') {
        return back()->with('error', 'Essa vaga já foi ocupada!');
    }

    Registro::create([
        'vaga_id' => $vaga->id,
        'placa'   => strtoupper($request->placa),
        'entrada' => now(),
    ]);

    $vaga->update(['status' => 'ocupada']);

    return back()->with('success', 'Vaga ' . $vaga->numero . ' reservada com sucesso! Placa: ' . strtoupper($request->placa));
}
// API - retorna vagas em JSON
public function vagasApi() {
    $vagas = Vaga::with('registroAtivo')->orderBy('numero')->get();
    return response()->json($vagas);
}

// API - reservar vaga pelo app mobile
public function reservarApi(Request $request) {
    $request->validate([
        'vaga_id' => 'required|exists:vagas,id',
        'placa'   => 'required|string|max:10',
    ]);

    $vaga = Vaga::findOrFail($request->vaga_id);

    if ($vaga->status === 'ocupada') {
        return response()->json(['error' => 'Vaga já ocupada!'], 400);
    }

    Registro::create([
        'vaga_id' => $vaga->id,
        'placa'   => strtoupper($request->placa),
        'entrada' => now(),
    ]);

    $vaga->update(['status' => 'ocupada']);

    return response()->json(['success' => 'Vaga reservada com sucesso!']);
}

// Limpar histórico
public function limparHistorico() {
    Registro::whereNotNull('saida')->delete();
    return back()->with('success', 'Histórico limpo com sucesso!');
}

}
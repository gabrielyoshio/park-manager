<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">🅿️ Painel do Gestor</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg">❌ {{ session('error') }}</div>
        @endif

        {{-- Cards de resumo --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                <p class="text-3xl font-bold text-gray-800">{{ $totalVagas }}</p>
                <p class="text-gray-500 mt-1 text-sm">Total de Vagas</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                <p class="text-3xl font-bold text-green-500">{{ $vagasLivres }}</p>
                <p class="text-gray-500 mt-1 text-sm">Livres</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                <p class="text-3xl font-bold text-red-500">{{ $vagasOcupadas }}</p>
                <p class="text-gray-500 mt-1 text-sm">Ocupadas</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                <p class="text-3xl font-bold text-blue-500">R$ {{ number_format($receitaHoje, 2, ',', '.') }}</p>
                <p class="text-gray-500 mt-1 text-sm">Receita Hoje</p>
            </div>
        </div>

        {{-- Formulário de entrada --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800">🚗 Registrar Entrada</h3>
            <form action="{{ route('entrada') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-medium">Vaga</label>
                    <select name="vaga_id" required class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Selecione...</option>
                        @foreach(\App\Models\Vaga::where('status','livre')->orderBy('numero')->get() as $vaga)
                            <option value="{{ $vaga->id }}">Vaga {{ $vaga->numero }} ({{ $vaga->tipo }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1 font-medium">Placa</label>
                    <input type="text" name="placa" placeholder="ABC-1234" required maxlength="10"
                        class="border border-gray-300 rounded-lg px-3 py-2 uppercase text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Registrar Entrada
                </button>
            </form>
        </div>

        {{-- Veículos no pátio --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">🅿️ Veículos no Pátio Agora</h3>
                <a href="{{ route('historico') }}" class="text-sm text-blue-500 hover:underline">Ver histórico completo →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="pb-3">Vaga</th>
                            <th class="pb-3">Placa</th>
                            <th class="pb-3">Entrada</th>
                            <th class="pb-3">Tempo</th>
                            <th class="pb-3">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrosAtivos as $reg)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 font-semibold text-gray-800">Vaga {{ $reg->vaga->numero }}</td>
                                <td class="py-3 text-gray-700">{{ $reg->placa }}</td>
                                <td class="py-3 text-gray-500">{{ $reg->entrada->format('H:i') }}</td>
                                <td class="py-3 text-gray-500">{{ $reg->entrada->diffForHumans(null, true) }}</td>
                                <td class="py-3">
                                    <form action="{{ route('saida', $reg) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg text-sm transition"
                                            onclick="return confirm('Registrar saída de {{ $reg->placa }}?')">
                                            Registrar Saída
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-gray-400">Nenhum veículo no pátio agora.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
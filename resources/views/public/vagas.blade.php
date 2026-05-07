<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>ParkManager - Vagas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <header class="bg-gray-900 shadow-lg py-5 px-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-3xl">🅿️</span>
            <h1 class="text-2xl font-bold text-blue-400">ParkManager</h1>
        </div>
        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">Acesso do gestor →</a>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-10">
        <h2 class="text-3xl font-bold mb-2">Vagas disponíveis</h2>
        <p class="text-gray-400 mb-8">Clique em uma vaga livre para reservar</p>

        {{-- Resumo --}}
        <div class="grid grid-cols-3 gap-4 mb-10">
            <div class="bg-gray-800 rounded-xl p-5 text-center">
                <p class="text-4xl font-bold">{{ $vagas->count() }}</p>
                <p class="text-gray-400 mt-1">Total de vagas</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-5 text-center">
                <p class="text-4xl font-bold text-green-400">{{ $vagas->where('status','livre')->count() }}</p>
                <p class="text-gray-400 mt-1">Livres</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-5 text-center">
                <p class="text-4xl font-bold text-red-400">{{ $vagas->where('status','ocupada')->count() }}</p>
                <p class="text-gray-400 mt-1">Ocupadas</p>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="bg-green-800 border border-green-500 text-green-200 px-4 py-3 rounded-lg mb-6">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-800 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-6">❌ {{ session('error') }}</div>
        @endif

        {{-- Grid de vagas --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            @foreach ($vagas as $vaga)
                @if($vaga->status === 'livre')
                    <button onclick="abrirModal({{ $vaga->id }}, {{ $vaga->numero }}, '{{ $vaga->tipo }}')"
                        class="rounded-xl p-4 text-center border-2 transition cursor-pointer hover:scale-105
                        bg-green-900 border-green-500 text-green-300 hover:bg-green-800">
                        <p class="text-2xl font-bold">{{ $vaga->numero }}</p>
                        <p class="text-xs uppercase mt-1">{{ $vaga->tipo }}</p>
                        <p class="text-sm font-semibold mt-2">✅ Livre</p>
                        <p class="text-xs mt-1 opacity-70">Clique para reservar</p>
                    </button>
                @else
                    <div class="rounded-xl p-4 text-center border-2 bg-red-900 border-red-500 text-red-300">
                        <p class="text-2xl font-bold">{{ $vaga->numero }}</p>
                        <p class="text-xs uppercase mt-1">{{ $vaga->tipo }}</p>
                        <p class="text-sm font-semibold mt-2">🔴 Ocupada</p>
                        @if($vaga->registroAtivo)
                            <p class="text-xs mt-1 opacity-70">{{ $vaga->registroAtivo->placa }}</p>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </main>

    {{-- Modal de reserva --}}
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-900 rounded-2xl p-8 w-full max-w-md shadow-2xl border border-gray-700">
            <h3 class="text-xl font-bold mb-1">🚗 Reservar Vaga</h3>
            <p class="text-gray-400 mb-2" id="modal-info">Vaga X - Tipo</p>
            <p class="text-yellow-400 text-sm mb-6" id="modal-preco">💰 R$ 15,00/hora — pagamento na saída</p>

            <form method="POST" action="{{ route('reservar') }}">
                @csrf
                <input type="hidden" name="vaga_id" id="modal-vaga-id">

                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-1">Seu nome</label>
                    <input type="text" name="nome" required placeholder="João Silva"
                        class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm text-gray-400 mb-1">Placa do veículo</label>
                    <input type="text" name="placa" required placeholder="ABC-1234" maxlength="8"
                        oninput="formatarPlaca(this)"
                        class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white uppercase focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Formato: ABC-1234 ou ABC-1A23</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition mb-3">
                    Confirmar Reserva
                </button>
            </form>

            <button type="button" onclick="fecharModal()" class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-semibold transition">
                Cancelar
            </button>
        </div>
    </div>

    <script>
        function abrirModal(id, numero, tipo) {
            document.getElementById('modal-vaga-id').value = id;
            document.getElementById('modal-info').textContent = 'Vaga ' + numero + ' - ' + tipo;
            const preco = tipo === 'carro' ? 'R$ 15,00/hora' : 'R$ 10,00/hora';
            document.getElementById('modal-preco').textContent = '💰 ' + preco + ' — pagamento na saída';
            document.getElementById('modal').classList.remove('hidden');
        }

        function fecharModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function formatarPlaca(input) {
            let valor = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (valor.length > 3) {
                valor = valor.substring(0, 3) + '-' + valor.substring(3, 7);
            }
            input.value = valor;
        }
    </script>

</body>
</html>
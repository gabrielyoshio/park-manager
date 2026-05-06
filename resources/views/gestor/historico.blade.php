<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center mb-4">
    <h2 class="font-semibold text-xl text-gray-800">Histórico de Registros</h2>
    <form action="{{ route('historico.limpar') }}" method="POST"
        onsubmit="return confirm('Tem certeza que deseja limpar todo o histórico?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
            🗑️ Limpar Histórico
        </button>
    </form>
</div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                            <th class="pb-2">Vaga</th>
                            <th class="pb-2">Placa</th>
                            <th class="pb-2">Entrada</th>
                            <th class="pb-2">Saída</th>
                            <th class="pb-2">Valor</th>
                            <th class="pb-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $reg)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-3 dark:text-white">Vaga {{ $reg->vaga->numero }}</td>
                                <td class="py-3 dark:text-white font-semibold">{{ $reg->placa }}</td>
                                <td class="py-3 text-gray-500 dark:text-gray-400">{{ $reg->entrada->format('d/m/Y H:i') }}</td>
                                <td class="py-3 text-gray-500 dark:text-gray-400">
                                    {{ $reg->saida ? $reg->saida->format('d/m/Y H:i') : '—' }}
                                </td>
                                <td class="py-3 dark:text-white">
                                    {{ $reg->valor ? 'R$ ' . number_format($reg->valor, 2, ',', '.') : '—' }}
                                </td>
                                <td class="py-3">
                                    @if($reg->saida)
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Concluído</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Em andamento</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-center text-gray-400">Nenhum registro encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $registros->links() }}</div>
        </div>
    </div>
</x-app-layout>
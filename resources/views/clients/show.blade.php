@extends('layouts.app')

@section('content')
    <div class="flex items-center gap-4 mb-4">
        <img src="{{ $client->profile_photo_url }}" class="w-16 h-16 rounded-full object-cover" alt="Foto">
        <div>
            <h1 class="text-2xl font-semibold">{{ $client->name }}</h1>
            <div class="text-gray-600">{{ $client->email }} • {{ $client->phone }}</div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white border rounded p-4">
            <h2 class="font-medium mb-3">Histórico de compras</h2>
            <form method="POST" action="{{ route('clients.purchases.store',$client) }}" class="grid md:grid-cols-3 gap-2 mb-3">
                @csrf
                <input type="date" name="purchased_at" class="border rounded px-2 py-2" value="{{ now()->toDateString() }}">
                <input type="number" step="0.01" name="amount" class="border rounded px-2 py-2" placeholder="Valor (R$)">
                <input type="text" name="description" class="border rounded px-2 py-2 md:col-span-2" placeholder="Descrição (opcional)">
                <button class="bg-blue-600 text-white rounded px-3">Adicionar</button>
            </form>
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Data</th>
                        <th class="p-2">Descrição</th>
                        <th class="p-2">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($client->purchases()->latest('purchased_at')->get() as $p)
                        <tr class="border-t">
                            <td class="p-2">{{ $p->purchased_at->format('d/m/Y') }}</td>
                            <td class="p-2">{{ $p->description }}</td>
                            <td class="p-2">R$ {{ number_format($p->amount,2,',','.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-3 text-center text-gray-500">Sem compras.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white border rounded p-4">
            <h2 class="font-medium mb-2">Resumo</h2>
            <div class="text-2xl">Total gasto: R$ {{ number_format($client->purchases()->sum('amount'),2,',','.') }}</div>
            <div class="text-gray-600 mt-1">Última compra:
                @php($last = $client->purchases()->latest('purchased_at')->first())
                    {{ $last ? $last->purchased_at->format('d/m/Y') : '—' }}
            </div>
        </div>
    </div>
@endsection
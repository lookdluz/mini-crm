@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Clientes</h1>
    <form method="GET" class="bg-white p-4 rounded border grid md:grid-cols-4 gap-3 mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, e-mail ou telefone" class="border rounded px-3 py-2 w-full">
        <input type="number" step="0.01" name="min_spent" value="{{ request('min_spent') }}" placeholder="Mín gasto (R$)" class="border rounded px-3 py-2 w-full">
        <input type="number" step="0.01" name="max_spent" value="{{ request('max_spent') }}" placeholder="Máx gasto (R$)" class="border rounded px-3 py-2 w-full">
        <button class="bg-blue-600 text-white rounded px-4">Filtrar</button>
    </form>

    <div class="bg-white rounded border overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-sm">
                <tr>
                    <th class="p-2">#</th>
                    <th class="p-2">Cliente</th>
                    <th class="p-2">Contato</th>
                    <th class="p-2">Compras</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $c)
                    <tr class="border-t">
                        <td class="p-2">{{ $c->id }}</td>
                        <td class="p-2 flex items-center gap-3">
                            <img src="{{ $c->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover" alt="Foto">
                            <div>
                                <a href="{{ route('clients.show',$c) }}" class="font-medium hover:underline">{{ $c->name }}</a>
                                <div class="text-xs text-gray-500">Criado em {{ $c->created_at->format('d/m/Y') }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div>{{ $c->email }}</div>
                            <div class="text-sm text-gray-600">{{ $c->phone }}</div>
                        </td>
                        <td class="p-2 text-sm">
                            <div>Qtd: {{ $c->purchases_count }}</div>
                        </td>
                        <td class="p-2 text-right">
                            <a href="{{ route('clients.edit',$c) }}" class="text-blue-600">Editar</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-4 text-center text-gray-500">Nenhum cliente encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $clients->links() }}</div>
@endsection
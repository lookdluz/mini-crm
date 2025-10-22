<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mini CRM</title>
        @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-800">
        <nav class="bg-white border-b shadow-sm">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                <a href="{{ route('clients.index') }}" class="font-semibold">Mini CRM</a>
                <div class="space-x-2">
                    <a href="{{ route('clients.export.csv', request()->query()) }}" class="px-3 py-1 border rounded">Exportar CSV</a>
                    <a href="{{ route('clients.export.pdf', request()->query()) }}" class="px-3 py-1 border rounded">Exportar PDF</a>
                    <a href="{{ route('clients.create') }}" class="px-3 py-1 bg-blue-600 text-white rounded">Novo Cliente</a>
                </div>
            </div>
        </nav>
        <main class="max-w-6xl mx-auto p-4">
            @if(session('ok'))
                <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded">{{ session('ok') }}</div>
            @endif
                {{ $slot ?? '' }}
                @yield('content')
        </main>
    </body>
</html>
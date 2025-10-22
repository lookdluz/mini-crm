@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Novo Cliente</h1>
    <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="bg-white p-4 rounded border grid gap-3 max-w-xl">
        @csrf
        <label class="grid gap-1">
            <span>Nome</span>
            <input name="name" value="{{ old('name') }}" class="border rounded px-3 py-2">
            @error('name')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>
        <label class="grid gap-1">
            <span>E-mail</span>
            <input name="email" type="email" value="{{ old('email') }}" class="border rounded px-3 py-2">
            @error('email')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>
        <label class="grid gap-1">
            <span>Telefone</span>
            <input name="phone" value="{{ old('phone') }}" class="border rounded px-3 py-2">
            @error('phone')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>
        <label class="grid gap-1">
            <span>Foto de perfil</span>
            <input name="profile_photo" type="file" accept="image/*" class="border rounded px-3 py-2">
            @error('profile_photo')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>

        <button class="bg-blue-600 text-white rounded px-4 py-2">Salvar</button>
    </form>
@endsection
@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Editar Cliente</h1>
    <form method="POST" action="{{ route('clients.update',$client) }}" enctype="multipart/form-data" class="bg-white p-4 rounded border grid gap-3 max-w-xl">
        @csrf @method('PUT')
        <div class="flex items-center gap-3">
            <img src="{{ $client->profile_photo_url }}" class="w-14 h-14 rounded-full object-cover" alt="Foto">
            <div class="text-sm text-gray-600">ID #{{ $client->id }}</div>
        </div>
        <label class="grid gap-1">
            <span>Nome</span>
            <input name="name" value="{{ old('name',$client->name) }}" class="border rounded px-3 py-2">
            @error('name')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>
        <label class="grid gap-1">
            <span>E-mail</span>
            <input name="email" type="email" value="{{ old('email',$client->email) }}" class="border rounded px-3 py-2">
            @error('email')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>
        <label class="grid gap-1">
            <span>Telefone</span>
            <input name="phone" value="{{ old('phone',$client->phone) }}" class="border rounded px-3 py-2">
            @error('phone')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>
        <label class="grid gap-1">
            <span>Foto de perfil (trocar)</span>
            <input name="profile_photo" type="file" accept="image/*" class="border rounded px-3 py-2">
            @error('profile_photo')<small class="text-red-600">{{ $message }}</small>@enderror
        </label>

        <button class="bg-blue-600 text-white rounded px-4 py-2">Atualizar</button>
    </form>
@endsection
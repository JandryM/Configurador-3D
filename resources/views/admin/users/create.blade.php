@extends('layouts.admin')

@section('title', 'Crear Usuario')

@section('content')
<div class="container mx-auto p-6 bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 rounded-2xl shadow-xl">
    <h1 class="text-4xl font-bold text-center text-slate-800 mb-8">Crear Nuevo Usuario</h1>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-lg font-medium text-slate-700">Nombre</label>
            <input type="text" name="name" id="name" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-lg font-medium text-slate-700">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Contraseña -->
        <div>
            <label for="password" class="block text-lg font-medium text-slate-700">Contraseña</label>
            <input type="password" name="password" id="password" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Rol -->
        <div>
            <label for="role" class="block text-lg font-medium text-slate-700">Rol</label>
            <select name="role" id="role" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @if(auth()->user()->isAdmin())
                    <option value="owner">Dueño</option>
                    <option value="seller">Vendedor</option>
                @elseif(auth()->user()->isOwner())
                    <option value="seller">Vendedor</option>
                @endif
            </select>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">Crear Usuario</button>
    </form>
</div>
@endsection
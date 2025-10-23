@extends('layouts.admin')

@section('title', 'Panel de Usuario')
@section('page-title', 'Dashboard del Cliente')

@section('content')
<div class="fade-in mb-8">
    <div class="glass-card rounded-2xl shadow-2xl p-8">
        <h1 class="text-3xl font-bold mb-2 text-slate-800">Bienvenido, {{ auth()->user()->name }}</h1>
        <p class="text-lg text-slate-600">Consulta tus pedidos y perfil desde aquí.</p>
    </div>
</div>

<div class="fade-in grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Ejemplo de sección personalizada para clientes -->
    <div class="glass-card rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-slate-800">Pedidos Recientes</h2>
        <p class="text-sm text-slate-600">Aquí aparecerán tus últimos pedidos.</p>
    </div>
</div>
@endsection
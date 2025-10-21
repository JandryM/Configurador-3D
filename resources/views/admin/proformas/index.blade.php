@extends('layouts.admin')

@section('title', 'Gestión de Proformas')
@section('page-title', 'Proformas')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-8">Gestión de Proformas</h1>
<livewire:admin.proformas-table />
@endsection
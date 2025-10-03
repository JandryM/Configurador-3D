<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 min-h-screen shadow-lg">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold">Q</span>
                    </div>
                    <span class="font-bold text-xl">Quality Admin</span>
                </div>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Administración</h3>
                </div>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-6 py-3 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 border-r-2 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center px-6 py-3 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.users') ? 'bg-gray-700 border-r-2 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" 
                           class="flex items-center px-6 py-3 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-700 border-r-2 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Productos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.materials') }}" 
                           class="flex items-center px-6 py-3 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.materials') ? 'bg-gray-700 border-r-2 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Materiales
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports') }}" 
                           class="flex items-center px-6 py-3 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.reports') ? 'bg-gray-700 border-r-2 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Reportes
                        </a>
                    </li>
                </ul>

                <div class="px-6 py-2 mt-8">
                    <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Usuario</h3>
                </div>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-6 py-3 text-sm hover:bg-gray-700">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Volver al sitio
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-6 py-3 text-sm hover:bg-gray-700 text-left">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">
                                {{ $title ?? 'Panel de Administración' }}
                            </h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">
                                {{ auth()->user()->name }}
                            </span>
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
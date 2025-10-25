<div class="container mx-auto p-6 bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 rounded-2xl shadow-xl">
    <h1 class="text-4xl font-bold text-center text-slate-800 mb-8">Crear Nuevo Usuario</h1>

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <strong>Por favor corrige los siguientes errores:</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Nombre -->
        <div>
            <label for="name" class="block text-lg font-medium text-slate-700">Nombre</label>
            <input type="text" id="name" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" wire:model.defer="name" required>
            @error('name')
                <span class="text-red-600 text-sm">{{ __($message) }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-lg font-medium text-slate-700">Correo Electrónico</label>
            <input type="email" id="email" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" wire:model.defer="email" required>
            @error('email')
                <span class="text-red-600 text-sm">{{ __($message) }}</span>
            @enderror
        </div>

        <!-- Contraseña -->
        <div>
            <label for="password" class="block text-lg font-medium text-slate-700">Contraseña</label>
            <input type="password" id="password" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" wire:model.defer="password" required>
            @error('password')
                <span class="text-red-600 text-sm">{{ __($message) }}</span>
            @enderror
        </div>

        <!-- Rol -->
        <div>
            <label for="role" class="block text-lg font-medium text-slate-700">Rol</label>
            <select id="role" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" wire:model.defer="role" required>
                @if(auth()->user()->isAdmin())
                    <option value="owner">Dueño</option>
                    <option value="seller">Vendedor</option>
                @elseif(auth()->user()->isOwner())
                    <option value="seller">Vendedor</option>
                @endif
            </select>
            @error('role')
                <span class="text-red-600 text-sm">{{ __($message) }}</span>
            @enderror
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">Crear Usuario</button>
    </form>
</div>

<div class="max-w-xl mx-auto mt-10">
    <div class="glass-card rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Editar Usuario</h2>
        @if (session()->has('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form wire:submit.prevent="update">
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Nombre</label>
                <input type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.defer="name" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input type="email" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.defer="email" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Rol</label>
                <select class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.defer="role" required>
                    <option value="admin">Administrador</option>
                    <option value="owner">Propietario</option>
                    <option value="seller">Vendedor</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Contraseña (opcional)</label>
                <input type="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.defer="password" placeholder="Nueva contraseña">
                <p class="text-xs text-slate-400 mt-1">Deja en blanco para mantener la contraseña actual.</p>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors font-semibold">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

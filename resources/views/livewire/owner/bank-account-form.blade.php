<div>
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6 max-w-lg mx-auto bg-white p-8 rounded-2xl shadow">
        <h2 class="text-xl font-bold mb-6 text-slate-800">Configuración de Cuenta Bancaria</h2>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Banco</label>
            <select wire:model.defer="bank_name" class="w-full px-3 py-2 border rounded-lg bg-slate-50" required>
                <option value="">Selecciona un banco</option>
                <option value="Banco Pichincha">Banco Pichincha</option>
                <option value="Banco Guayaquil">Banco Guayaquil</option>
                <option value="Banco del Pacífico">Banco del Pacífico</option>
                <option value="Produbanco">Produbanco</option>
                <option value="Banco Internacional">Banco Internacional</option>
                <option value="Banco Bolivariano">Banco Bolivariano</option>
                <option value="Banco de Loja">Banco de Loja</option>
                <option value="Banco Solidario">Banco Solidario</option>
                <option value="Banco Amazonas">Banco Amazonas</option>
                <option value="Banco ProCredit">Banco ProCredit</option>
            </select>
            @error('bank_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Número de cuenta</label>
            <input type="text" wire:model.defer="account_number" class="w-full px-3 py-2 border rounded-lg" required>
            @error('account_number') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de cuenta</label>
            <select wire:model.defer="account_type" class="w-full px-3 py-2 border rounded-lg bg-slate-50">
                <option value="">Selecciona el tipo</option>
                <option value="Corriente">Cuenta Corriente</option>
                <option value="Ahorros">Cuenta de Ahorros</option>
            </select>
            @error('account_type') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Identificación (CI/RUC)</label>
            <input type="text" wire:model.defer="identification" class="w-full px-3 py-2 border rounded-lg">
            @error('identification') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Titular de la cuenta</label>
            <input type="text" wire:model.defer="holder_name" class="w-full px-3 py-2 border rounded-lg" required>
            @error('holder_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
            <input type="text" wire:model.defer="phone" class="w-full px-3 py-2 border rounded-lg bg-slate-100" readonly>
            @error('phone') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">Guardar</button>
        </div>
    </form>
</div>

<div>
    @if($showModal)
        <div class="fixed inset-0 z-[1300] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="relative w-full max-w-xl mx-auto text-left">
                <div class="bg-white rounded-2xl shadow-2xl w-full">
                    <!-- Header -->
                    <!-- Header -->
                    <div class="bg-custom-blue/95 p-6 rounded-t-2xl border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Configuración de Cuenta Bancaria</h3>
                                    <p class="text-sm text-slate-400">Configura los datos de tu cuenta para recibir pagos</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg p-2 transition-colors cursor-pointer">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!-- Contenido -->
                    <form wire:submit.prevent="save" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Banco -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Banco
                                </label>
                                <select wire:model.defer="bank_name" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
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
                                @error('bank_name') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Tipo de cuenta -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Tipo de cuenta
                                </label>
                                <select wire:model.defer="account_type" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    <option value="">Selecciona el tipo</option>
                                    <option value="Corriente">Cuenta Corriente</option>
                                    <option value="Ahorros">Cuenta de Ahorros</option>
                                </select>
                                @error('account_type') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Número de cuenta -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Número de cuenta
                                </label>
                                <input type="text" wire:model.defer="account_number" placeholder="0000000000" required pattern="^[0-9]{10}$" maxlength="10" minlength="10" inputmode="numeric" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                @error('account_number') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Identificación -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Identificación (CI/RUC)
                                </label>
                                <input type="text" wire:model.defer="identification" placeholder="1234567890" required pattern="^[0-9]{10,13}$" maxlength="13" minlength="10" inputmode="numeric" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                @error('identification') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Titular de la cuenta -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Titular de la cuenta
                                </label>
                                <input type="text" wire:model.defer="holder_name" placeholder="Juan Pérez" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" />
                                @error('holder_name') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Teléfono
                                </label>
                                <input type="text" wire:model.defer="phone" readonly class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-100 text-sm text-slate-400 cursor-not-allowed" />
                                @error('phone') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!-- Acciones -->
                        <div class="flex gap-3 mt-6">
                            <button type="button" wire:click="closeModal" class="flex-1 py-3 rounded-xl font-semibold text-base bg-slate-200 text-slate-700 hover:bg-slate-300 transition cursor-pointer">Cancelar</button>
                            <button type="submit" class="flex-1 py-3 rounded-xl font-semibold text-base bg-blue-600 hover:bg-blue-700 text-white shadow-lg transition-all cursor-pointer">Guardar Cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

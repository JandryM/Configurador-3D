<div>
    @if($showModal)
        <div class="fixed inset-0 z-[1300] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="relative w-full max-w-xl mx-auto text-left">
                <div class="bg-white rounded-2xl shadow-2xl w-full">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-6 rounded-t-2xl text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">Configuración de Cuenta Bancaria</h3>
                                    <p class="text-sm text-white/80">Configura los datos de tu cuenta para recibir pagos</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeModal" class="hover:text-white/100 text-white/80 transition-colors">
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
                                    <svg class="w-4 h-4 inline-block mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 003 8v7a1 1 0 100 2h14a1 1 0 100-2V8a1 1 0 00.496-1.868l-7-4zM6 9a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1zm3 1a1 1 0 012 0v3a1 1 0 11-2 0v-3zm5-1a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"></path>
                                    </svg>
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
                                    <svg class="w-4 h-4 inline-block mr-1 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
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
                                    <svg class="w-4 h-4 inline-block mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Número de cuenta
                                </label>
                                <input type="text" wire:model.defer="account_number" placeholder="0000000000" required pattern="^[0-9]{10}$" maxlength="10" minlength="10" inputmode="numeric" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                @error('account_number') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Identificación -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Identificación (CI/RUC)
                                </label>
                                <input type="text" wire:model.defer="identification" placeholder="1234567890" required pattern="^[0-9]{10,13}$" maxlength="13" minlength="10" inputmode="numeric" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                @error('identification') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Titular de la cuenta -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Titular de la cuenta
                                </label>
                                <input type="text" wire:model.defer="holder_name" placeholder="Juan Pérez" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" />
                                @error('holder_name') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                    Teléfono
                                </label>
                                <input type="text" wire:model.defer="phone" readonly class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-100 text-sm text-slate-400 cursor-not-allowed" />
                                @error('phone') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!-- Acciones -->
                        <div class="flex gap-3 mt-6">
                            <button type="button" wire:click="closeModal" class="flex-1 py-3 rounded-xl font-semibold text-base bg-slate-200 text-slate-700 hover:bg-slate-300 transition">Cancelar</button>
                            <button type="submit" class="flex-1 py-3 rounded-xl font-semibold text-base bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-lg hover:from-blue-700 hover:to-indigo-700 transition">💾 Guardar Cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

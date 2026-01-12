<div>
    @if($showModal)
        <style>
            /* Modal base styles: force reset and isolation */
            .bank-modal * {
                box-sizing: border-box !important;
                font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
                margin: 0;
                padding: 0;
                border: none;
                outline: none;
            }
            .bank-modal {
                all: initial;
                font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
                box-sizing: border-box !important;
                position: fixed !important;
                inset: 0 !important;
                z-index: 1300 !important;
                overflow-y: auto !important;
            }
            .bank-modal .modal-content {
                background: #fff !important;
                border-radius: 1.25rem !important;
                box-shadow: 0 8px 32px rgba(0,0,0,0.18) !important;
                width: 100% !important;
                max-width: 32rem !important;
                margin: auto !important;
                text-align: left !important;
                position: relative !important;
            }
            .bank-modal .modal-header {
                background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%) !important;
                padding: 1rem 1.5rem !important;
                border-radius: 1.25rem 1.25rem 0 0 !important;
                color: #fff !important;
            }
            .bank-modal .modal-header h3 {
                font-size: 1.125rem !important;
                font-weight: bold !important;
                color: #fff !important;
            }
            .bank-modal .modal-header p {
                font-size: 0.875rem !important;
                color: rgba(255,255,255,0.8) !important;
            }
            .bank-modal .modal-header button {
                background: none !important;
                border: none !important;
                color: rgba(255,255,255,0.8) !important;
                cursor: pointer !important;
                transition: color 0.2s;
            }
            .bank-modal .modal-header button:hover {
                color: #fff !important;
            }
            .bank-modal form {
                padding: 1.5rem !important;
            }
            .bank-modal label {
                font-size: 0.875rem !important;
                font-weight: 600 !important;
                color: #334155 !important;
                margin-bottom: 0.5rem !important;
                display: block !important;
            }
            .bank-modal select, .bank-modal input[type="text"] {
                width: 100% !important;
                padding: 0.75rem 1rem !important;
                border: 2px solid #e2e8f0 !important;
                border-radius: 0.75rem !important;
                background: #f8fafc !important;
                font-size: 0.875rem !important;
                color: #334155 !important;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .bank-modal select:focus, .bank-modal input[type="text"]:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 2px #3b82f6 !important;
            }
            .bank-modal select:hover, .bank-modal input[type="text"]:hover {
                border-color: #60a5fa !important;
            }
            .bank-modal input[readonly] {
                background: #f1f5f9 !important;
                color: #64748b !important;
                cursor: not-allowed !important;
            }
            .bank-modal .text-red-600 {
                color: #dc2626 !important;
                font-size: 0.75rem !important;
                margin-top: 0.25rem !important;
                display: block !important;
            }
            .bank-modal .actions {
                margin-top: 1.5rem !important;
                display: flex !important;
                gap: 0.75rem !important;
            }
            .bank-modal .actions button {
                flex: 1 1 0 !important;
                padding: 0.75rem 1rem !important;
                border-radius: 0.75rem !important;
                font-weight: 600 !important;
                font-size: 1rem !important;
                transition: background 0.2s, color 0.2s;
                box-shadow: 0 2px 8px rgba(59,130,246,0.08) !important;
            }
            .bank-modal .actions .cancel {
                background: #e2e8f0 !important;
                color: #334155 !important;
            }
            .bank-modal .actions .cancel:hover {
                background: #cbd5e1 !important;
            }
            .bank-modal .actions .save {
                background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%) !important;
                color: #fff !important;
                box-shadow: 0 4px 16px rgba(59,130,246,0.12) !important;
            }
            .bank-modal .actions .save:hover {
                background: linear-gradient(90deg, #2563eb 0%, #4f46e5 100%) !important;
            }
        </style>
        <div class="bank-modal">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3>Configuración de Cuenta Bancaria</h3>
                                    <p>Configura los datos de tu cuenta para recibir pagos</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeModal">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!-- Contenido -->
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Banco -->
                            <div class="md:col-span-2">
                                <label>
                                    <svg class="w-4 h-4 inline-block mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 003 8v7a1 1 0 100 2h14a1 1 0 100-2V8a1 1 0 00.496-1.868l-7-4zM6 9a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1zm3 1a1 1 0 012 0v3a1 1 0 11-2 0v-3zm5-1a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"></path>
                                    </svg>
                                    Banco
                                </label>
                                <select wire:model.defer="bank_name" required>
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
                                @error('bank_name') <span class="text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <!-- Tipo de cuenta -->
                            <div>
                                <label>
                                    <svg class="w-4 h-4 inline-block mr-1 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tipo de cuenta
                                </label>
                                <select wire:model.defer="account_type">
                                    <option value="">Selecciona el tipo</option>
                                    <option value="Corriente">Cuenta Corriente</option>
                                    <option value="Ahorros">Cuenta de Ahorros</option>
                                </select>
                                @error('account_type') <span class="text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <!-- Número de cuenta -->
                            <div>
                                <label>
                                    <svg class="w-4 h-4 inline-block mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Número de cuenta
                                </label>
                                <input type="text" wire:model.defer="account_number" placeholder="0000000000" required>
                                @error('account_number') <span class="text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <!-- Identificación -->
                            <div>
                                <label>
                                    <svg class="w-4 h-4 inline-block mr-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Identificación (CI/RUC)
                                </label>
                                <input type="text" wire:model.defer="identification" placeholder="1234567890">
                                @error('identification') <span class="text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <!-- Titular de la cuenta -->
                            <div>
                                <label>
                                    <svg class="w-4 h-4 inline-block mr-1 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Titular de la cuenta
                                </label>
                                <input type="text" wire:model.defer="holder_name" placeholder="Juan Pérez" required>
                                @error('holder_name') <span class="text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <label>
                                    <svg class="w-4 h-4 inline-block mr-1 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                    Teléfono
                                </label>
                                <input type="text" wire:model.defer="phone" readonly>
                                @error('phone') <span class="text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!-- Acciones -->
                        <div class="actions">
                            <button type="button" wire:click="closeModal" class="cancel">Cancelar</button>
                            <button type="submit" class="save">💾 Guardar Cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

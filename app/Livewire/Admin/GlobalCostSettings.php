<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\GlobalCostSetting;
use Carbon\Carbon;

class GlobalCostSettings extends Component
{
    public $currentSetting;
    public $indirect_cost_percentage;
    public $valid_from;
    public $valid_until;
    public $duration_months = 1; // Por defecto 1 mes
    public $custom_duration = false;
    public $isEditingExisting = false; // Nueva propiedad para saber si estamos editando
    
    // Modal de confirmación
    public $showConfirmModal = false;
    public $pendingAction = null; // 'create' o 'update'

    public function mount()
    {
        $this->loadCurrentSetting();
        $this->initializeDates();
    }

    public function loadCurrentSetting()
    {
        $this->currentSetting = GlobalCostSetting::current()->first();
        
        if ($this->currentSetting) {
            // Verificar y actualizar el bloqueo si está vigente
            if ($this->currentSetting->isValid() && !$this->currentSetting->is_locked) {
                $this->currentSetting->update(['is_locked' => true]);
                $this->currentSetting->refresh();
            }
            
            $this->indirect_cost_percentage = $this->currentSetting->indirect_cost_percentage;
        } else {
            $this->indirect_cost_percentage = 0;
        }
        
        $this->isEditingExisting = false;
    }

    public function enableEditMode()
    {
        if (!$this->currentSetting) {
            session()->flash('error', 'No hay configuración para editar.');
            return;
        }

        // Solo administradores y dueños pueden editar
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'owner'])) {
            session()->flash('error', 'No tienes permisos para editar la configuración.');
            return;
        }
        // Solo el usuario creador puede editar
        if ($this->currentSetting->user_id !== $user->id) {
            session()->flash('error', 'Solo el usuario que creó la configuración puede editarla.');
            return;
        }
        if (!$this->currentSetting->canBeEdited()) {
            session()->flash('error', "Ya no puedes editar esta configuración. La ventana de edición se cerró.");
            return;
        }
        $this->isEditingExisting = true;
        // Cargar datos actuales para editar
        $this->indirect_cost_percentage = $this->currentSetting->indirect_cost_percentage;
        $this->valid_from = $this->currentSetting->valid_from ? $this->currentSetting->valid_from->format('Y-m-d') : now()->format('Y-m-d');
        $this->valid_until = $this->currentSetting->valid_until ? $this->currentSetting->valid_until->format('Y-m-d') : null;
        $timeLeft = $this->currentSetting->getTimeUntilEditWindowCloses();
        $remainingEdits = 2 - $this->currentSetting->edit_attempts;
        session()->flash('message', "Modo de edición activado. Tienes {$remainingEdits} intentos" . ($timeLeft ? " y {$timeLeft}" : "") . ".");
    }

    public function cancelEdit()
    {
        $this->isEditingExisting = false;
        $this->loadCurrentSetting();
        $this->initializeDates();
    }

    public function openConfirmModal($action)
    {
        $this->pendingAction = $action;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->pendingAction = null;
    }

    public function confirmAction()
    {
        if ($this->pendingAction === 'update') {
            $this->performUpdate();
        } elseif ($this->pendingAction === 'create') {
            $this->performCreate();
        }
        
        $this->closeConfirmModal();
    }

    private function initializeDates()
    {
        $this->valid_from = now()->format('Y-m-d');
        $this->duration_months = 1;
        $this->updateValidUntil();
    }

    public function toggleCustomDuration()
    {
        $this->custom_duration = !$this->custom_duration;
        
        if (!$this->custom_duration) {
            // Si desactiva personalizado, recalcular con duración en meses
            $this->updateValidUntil();
        }
    }

    public function updateValidUntil()
    {
        if (!$this->custom_duration) {
            $validFrom = $this->valid_from ? \Carbon\Carbon::parse($this->valid_from) : now();
            $months = (float) $this->duration_months;
            
            // Si es 0.5 meses (15 días), usar addDays en lugar de addMonths
            if ($months == 0.5) {
                $this->valid_until = $validFrom->copy()->addDays(15)->format('Y-m-d');
            } else {
                $this->valid_until = $validFrom->copy()->addMonths($months)->format('Y-m-d');
            }
        }
    }

    public function updatedDurationMonths()
    {
        $this->updateValidUntil();
    }

    public function updatedValidFrom()
    {
        $this->updateValidUntil();
    }

    public function save()
    {
        $this->validate([
            'indirect_cost_percentage' => 'required|numeric|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        // Solo administradores y dueños pueden crear o editar
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'owner'])) {
            session()->flash('error', 'No tienes permisos para crear o editar la configuración.');
            $this->isEditingExisting = false;
            $this->loadCurrentSetting();
            return;
        }
        // Determinar si es actualización o creación
        if ($this->isEditingExisting && $this->currentSetting) {
            // Solo el usuario creador puede editar
            if ($this->currentSetting->user_id !== $user->id) {
                session()->flash('error', 'Solo el usuario que creó la configuración puede editarla.');
                $this->isEditingExisting = false;
                $this->loadCurrentSetting();
                return;
            }
            if (!$this->currentSetting->canBeEdited()) {
                session()->flash('error', "Ya no puedes editar. La ventana de edición se cerró.");
                $this->isEditingExisting = false;
                $this->loadCurrentSetting();
                return;
            }
            // Abrir modal de confirmación para actualización
            $this->openConfirmModal('update');
        } else {
            // Solo administradores y dueños pueden crear
            $this->openConfirmModal('create');
        }
    }

    private function performUpdate()
    {
        // Actualizar la configuración existente
        $validFrom = !empty($this->valid_from) ? \Carbon\Carbon::parse($this->valid_from) : null;
        $validUntil = !empty($this->valid_until) ? \Carbon\Carbon::parse($this->valid_until) : null;

        $this->currentSetting->update([
            'indirect_cost_percentage' => $this->indirect_cost_percentage,
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
        ]);

        // Registrar el intento de edición
        $this->currentSetting->recordEdit();
        
        $remainingEdits = 2 - $this->currentSetting->edit_attempts;
        $timeLeft = $this->currentSetting->getTimeUntilEditWindowCloses();
        
        $message = "Configuración actualizada exitosamente.";
        if ($remainingEdits > 0 && $timeLeft) {
            $message .= " Te quedan {$remainingEdits} ediciones y {$timeLeft}.";
        } else {
            $message .= " Ya no puedes editar más.";
        }
        
        session()->flash('message', $message);
        $this->isEditingExisting = false;
        $this->loadCurrentSetting();
        $this->initializeDates();
        
        // Notificar al dashboard sobre la actualización
        $this->dispatch('costos-actualizados');
    }

    private function performCreate()
    {
        // Convertir strings a Carbon
        $validFrom = !empty($this->valid_from) ? \Carbon\Carbon::parse($this->valid_from) : null;
        $validUntil = !empty($this->valid_until) ? \Carbon\Carbon::parse($this->valid_until) : null;

        // Crear nueva configuración bloqueada y asociar al usuario actual
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'owner'])) {
            session()->flash('error', 'No tienes permisos para crear la configuración.');
            return;
        }
        GlobalCostSetting::create([
            'user_id' => $user->id,
            'indirect_cost_percentage' => $this->indirect_cost_percentage,
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'is_locked' => true,
            'edit_attempts' => 0,
        ]);

        session()->flash('message', 'Configuración de costos guardada exitosamente.');
        $this->loadCurrentSetting();
        $this->initializeDates();
        
        // Notificar al dashboard sobre la creación
        $this->dispatch('costos-actualizados');
    }

    public function render()
    {
        return view('livewire.admin.global-cost-settings')->layout('partials.sidebar');
    }
}

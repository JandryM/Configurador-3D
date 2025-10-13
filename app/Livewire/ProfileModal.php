<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileModal extends Component
{
    public $isOpen = false;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $province = '';
    public $city = '';
    public $cities = [];
    public $showSuccessMessage = false;
    
    protected $listeners = [
        'openProfileModal' => 'openModal',
        'closeProfileModal' => 'closeModal'
    ];
    
    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->address = $user->address ?? '';
        $this->province = $user->province ?? '';
        $this->city = $user->city ?? '';
        
        if (!empty($this->province)) {
            $this->cities = config('ecuador.provinces')[$this->province] ?? [];
        }
    }
    
    public function openModal()
    {
        $this->isOpen = true;
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->showSuccessMessage = false;
    }
    
    public function updatedProvince($value)
    {
        if (!empty($value)) {
            $this->cities = config('ecuador.provinces')[$value] ?? [];
            $this->city = '';
        } else {
            $this->cities = [];
        }
    }
    
    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'province' => 'required|string|in:' . implode(',', array_keys(config('ecuador.provinces'))),
            'city' => 'required|string',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'phone.required' => 'El teléfono es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
            'province.required' => 'La provincia es obligatoria.',
            'city.required' => 'La ciudad es obligatoria.',
        ]);
        
        User::where('id', Auth::id())->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
        ]);
        
        // Mostrar mensaje de éxito
        $this->showSuccessMessage = true;
        
        // Cerrar modal después de 2 segundos
        $this->dispatch('close-modal-after-delay');
        
        // Refrescar para actualizar el header
        $this->dispatch('profile-updated');
    }
    
    public function resendVerification()
    {
        $user = Auth::user();
        
        if (!is_null($user->email_verified_at)) {
            session()->flash('verification-success', 'Tu correo ya está verificado.');
            // Auto-close success message after 2 seconds
            $this->dispatch('auto-close-message', type: 'verification-success');
            return;
        }
        
        // Abrir el modal de verificación
        $this->dispatch('openVerificationModal');
    }
    
    public function render()
    {
        return view('livewire.profile-modal');
    }
}
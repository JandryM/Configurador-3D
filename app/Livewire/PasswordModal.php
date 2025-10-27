<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class PasswordModal extends Component
{
    public $isOpen = false;
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $showCurrentPassword = false;
    public $showNewPassword = false;
    public $showConfirmPassword = false;
    public $showSuccessMessage = false;
    
    protected $listeners = [
        'openPasswordModal' => 'openModal',
        'closePasswordModal' => 'closeModal'
    ];
    
    public function openModal()
    {
        // Solo permitir a usuarios no OAuth
        if (Auth::user()->oauth_provider) {
            session()->flash('error', 'El cambio de contraseña no está disponible para cuentas vinculadas a Google.');
            return;
        }
        
        $this->isOpen = true;
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->showCurrentPassword = false;
        $this->showNewPassword = false;
        $this->showConfirmPassword = false;
        $this->showSuccessMessage = false;
    }
    
    public function toggleCurrentPassword()
    {
        $this->showCurrentPassword = !$this->showCurrentPassword;
    }
    
    public function toggleNewPassword()
    {
        $this->showNewPassword = !$this->showNewPassword;
    }
    
    public function toggleConfirmPassword()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }
    
    public function updatePassword()
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => [
                    'required', 
                    'string', 
                    Password::defaults(), 
                    'confirmed',
                    function ($attribute, $value, $fail) {
                        if (Hash::check($value, Auth::user()->password)) {
                            $fail('La nueva contraseña debe ser diferente a la actual.');
                        }
                    }
                ],
            ], [
                'current_password.required' => 'La contraseña actual es obligatoria.',
                'current_password.current_password' => 'La contraseña actual es incorrecta.',
                'password.required' => 'La nueva contraseña es obligatoria.',
                'password.confirmed' => 'La confirmación de contraseña no coincide.',
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        User::where('id', Auth::id())->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Mostrar mensaje de éxito
        $this->showSuccessMessage = true;
        
        // Cerrar modal después de 2 segundos
        $this->dispatch('close-modal-after-delay-password');
    }
    
    public function render()
    {
        return view('livewire.auth.password-modal');
    }
}
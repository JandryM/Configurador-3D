<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Livewire\Component;

class VerificationModal extends Component
{
    public $showModal = false;
    public $showSuccessMessage = false;
    public $successMessage = '';

    protected $listeners = ['openVerificationModal' => 'openModal'];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->resetErrorBag();
    }

    public function sendVerification()
    {
        $user = Auth::user();
        
        if ($user->email_verified_at) {
            // Usuario ya está verificado
            $this->successMessage = 'Tu correo electrónico ya está verificado.';
            $this->showSuccessMessage = true;
            
            // Cerrar modal después de 2 segundos
            $this->dispatch('close-verification-modal-after-delay');
            return;
        }

        if ($user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
        }

        $this->successMessage = 'Se ha enviado un nuevo enlace de verificación a tu correo electrónico.';
        $this->showSuccessMessage = true;

        // Cerrar modal después de 2 segundos
        $this->dispatch('close-verification-modal-after-delay');
    }

    public function render()
    {
        return view('livewire.verification-modal');
    }
}
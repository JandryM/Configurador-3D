<?php
namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Notifications\VerifyCodeNotification;
use Livewire\Component;

class VerificationModal extends Component
{
    // Verificación automática en tiempo real
    public function checkInputCode()
    {
        if (strlen($this->input_code) === 6 && ctype_digit($this->input_code)) {
            $this->verifyCode();
        }
    }
    /**
     * Cerrar sesión desde el modal de verificación
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public $showModal = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $code = '';
    public $input_code = '';
    public $codeSent = false;

    protected $listeners = ['openVerificationModal' => 'openModal'];

    public function mount()
    {
        $user = Auth::user();
        if ($user && !$user->email_verified_at) {
            $this->showModal = true;
        }
    }

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
            $this->successMessage = 'Tu correo electrónico ya está verificado.';
            $this->showSuccessMessage = true;
            $this->dispatch('close-verification-modal-after-delay');
            return;
        }

        // Generar código aleatorio de 6 dígitos
        $this->code = random_int(100000, 999999);
        // Guardar el código en cache por 10 minutos
        Cache::put('verify_code_' . $user->id, $this->code, now()->addMinutes(10));

        // Enviar notificación con el código
        $user->notify(new VerifyCodeNotification($this->code));

        $this->successMessage = 'Se ha enviado un código de verificación a tu correo electrónico.';
        $this->showSuccessMessage = true;
        $this->codeSent = true;
    }

    public function verifyCode()
    {
        $user = Auth::user();
        $code = Cache::get('verify_code_' . $user->id);
        if ($code && $this->input_code == $code) {
            $user->email_verified_at = now();
            $user->save();
            Cache::forget('verify_code_' . $user->id);
            $this->successMessage = '¡Correo verificado correctamente!';
            $this->showSuccessMessage = true;
            $this->showModal = false;
            $this->reset('input_code');
            $this->dispatch('email-verified-success');
        } else {
            $this->addError('input_code', 'El código ingresado es incorrecto o ha expirado.');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email', [
            'codeSent' => $this->codeSent,
        ]);
    }
}
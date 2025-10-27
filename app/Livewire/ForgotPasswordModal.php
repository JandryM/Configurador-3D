<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\ResetPasswordCodeNotification;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordModal extends Component
{
    public $isOpen = false;
    public $email = '';
    public $code = '';
    public $new_password = '';
    public $new_password_confirmation = '';
    public $step = 1; // 1: pedir email, 2: pedir código, 3: nueva contraseña
    public $showSuccessMessage = false;
    public $showErrorMessage = false;
    public $errorMessage = '';
    public $showPassword = false;
    public $showPasswordConfirmation = false;
    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordConfirmation()
    {
        $this->showPasswordConfirmation = !$this->showPasswordConfirmation;
    }

    protected $listeners = [
        'openForgotPasswordModal' => 'openModal',
        'closeForgotPasswordModal' => 'closeModal'
    ];

    public function openModal()
    {
        $this->isOpen = true;
        $this->reset('email', 'code', 'new_password', 'new_password_confirmation', 'showSuccessMessage', 'showErrorMessage', 'errorMessage', 'showPassword', 'showPasswordConfirmation');
        $this->showPassword = false;
        $this->showPasswordConfirmation = false;
        $this->step = 1;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset('email', 'code', 'new_password', 'new_password_confirmation', 'showSuccessMessage', 'showErrorMessage', 'errorMessage');
        $this->step = 1;
    }

    public function sendResetCode()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El formato del correo no es válido.',
            'email.exists' => 'No existe una cuenta con ese correo.',
        ]);

        $user = User::where('email', $this->email)->first();
        $code = random_int(100000, 999999);
        session(['reset_password_code_' . $this->email => $code]);
        $user->notify(new \App\Notifications\ResetPasswordCodeNotification($code));
        $this->step = 2;
    }

    public function verifyCode()
    {
        $this->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'El código es obligatorio.',
            'code.digits' => 'El código debe tener 6 dígitos.',
        ]);

        $sessionCode = session('reset_password_code_' . $this->email);
        if ($sessionCode && $sessionCode == $this->code) {
            $this->step = 3;
            $this->showSuccessMessage = false;
        } else {
            $this->showErrorMessage = true;
            $this->errorMessage = 'El código ingresado es incorrecto.';
        }
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'La confirmación no coincide.',
        ]);

        $sessionCode = session('reset_password_code_' . $this->email);
        $user = User::where('email', $this->email)->first();
        if ($user && $sessionCode && $sessionCode == $this->code) {
            $user->password = Hash::make($this->new_password);
            $user->save();
            session()->forget('reset_password_code_' . $this->email);
            $this->showSuccessMessage = true;
            $this->step = 4; // Paso especial para mostrar solo el mensaje final
            $this->reset('code', 'new_password', 'new_password_confirmation', 'showPassword', 'showPasswordConfirmation');
            $this->dispatch('close-modal-after-delay');
        } else {
            $this->showErrorMessage = true;
            $this->errorMessage = 'El código es inválido.';
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-modal');
    }
}

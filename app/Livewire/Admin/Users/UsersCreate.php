<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UsersCreate extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'client';
    public bool $showConfirmModal = false;

    public function save()
    {
        $currentUser = Auth::user();
        
        // Determinar roles permitidos según el usuario autenticado
        $allowedRoles = [];
        if ($currentUser->isAdmin()) {
            $allowedRoles = ['admin', 'owner', 'client'];
        } elseif ($currentUser->isOwner()) {
            $allowedRoles = ['owner', 'client'];
        }
        
        $rolesRule = 'required|in:' . implode(',', $allowedRoles);
        
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => $rolesRule,
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe tener más de 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es válido para tu perfil.',
        ]);
        $this->showConfirmModal = true;
    }

    public function confirmCreateUser()
    {
        $currentUser = Auth::user();
        
        // Validar permisos finales
        if ($currentUser->isOwner() && $this->role === 'admin') {
            session()->flash('error', 'No tienes permiso para asignar el rol de administrador.');
            $this->showConfirmModal = false;
            return;
        }
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'email_verified_at' => now(),
            'oauth_provider' => 'local',
        ]);
        $this->reset(['name', 'email', 'password', 'role']);
        $this->showConfirmModal = false;
        $this->dispatch('user-created');
        $this->dispatch('close-modal');
        session()->flash('success', 'Usuario creado exitosamente.');
    }

    public function cancelCreateUser()
    {
        $this->showConfirmModal = false;
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}

<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UsersEdit extends Component
{
    public ?User $user = null;
    public string $name = '';
    public string $email = '';
    public string $role = '';
    public string $password = '';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'role' => 'required|in:owner,seller,admin',
        ]);
        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->role = $this->role;
        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        }
        $this->user->save();
        session()->flash('success', 'Usuario actualizado exitosamente.');
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.edit')->layout('partials.sidebar');
    }
}

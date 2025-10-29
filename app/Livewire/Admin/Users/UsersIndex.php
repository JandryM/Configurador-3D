<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;


class UsersIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $roleFilter = 'all';
    public bool $showUserModal = false;
    public bool $showUserDetailsModal = false;
    public ?User $selectedUser = null;
    public ?User $userDetails = null;
    public string $actionType = '';
    public string $actionReason = '';
    public string $suspensionDays = '7';

    public function render()
    {
        $authUser = auth()->user();
        $query = User::query();
        // Filtrar por rol del usuario autenticado
        if ($authUser->isAdmin()) {
            $query->whereIn('role', ['owner', 'seller', 'client']);
        } elseif ($authUser->isOwner()) {
            $query->whereIn('role', ['seller', 'client']);
        } elseif ($authUser->isSeller()) {
            $query->where('role', 'client');
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('email', 'ilike', '%'.$this->search.'%');
            });
        }
        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }
        switch ($this->statusFilter) {
            case 'active':
                $query->where('is_suspended', false);
                break;
            case 'suspended':
                $query->where('is_suspended', true);
                break;
            case 'unverified':
                $query->whereNull('email_verified_at');
                break;
        }
        $users = $query->orderBy('created_at', 'desc')->paginate(4);
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $suspendedUsers = User::where('is_suspended', true)->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $recentLogins = User::where('last_login_at', '>=', now()->subDays(7))->count();
        return view('livewire.admin.users.index', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'adminUsers' => $adminUsers,
            'suspendedUsers' => $suspendedUsers,
            'verifiedUsers' => $verifiedUsers,
            'recentLogins' => $recentLogins,
        ])->layout('partials.sidebar');
    }

    public function openUserModal(User $user, string $action)
    {
        $this->selectedUser = $user;
        $this->actionType = $action;
        $this->actionReason = '';
        $this->suspensionDays = '7';
        $this->showUserModal = true;
    }

    public function openUserDetailsModal(User $user)
    {
        $this->userDetails = $user;
        $this->showUserDetailsModal = true;
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
        $this->actionType = '';
        $this->actionReason = '';
        $this->suspensionDays = '7';
    }

    public function closeUserDetailsModal()
    {
        $this->showUserDetailsModal = false;
        $this->userDetails = null;
    }

    public function suspend()
    {
        if (! $this->selectedUser) return;
        if ($this->selectedUser->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            session()->flash('error', 'No se puede suspender al último administrador del sistema.');
            return;
        }
        $this->validate([
            'suspensionDays' => 'required|in:1,7,30,90,365,permanent',
            'actionReason' => 'required|string|max:500',
        ]);
        $until = $this->suspensionDays !== 'permanent' ? Carbon::now()->addDays((int) $this->suspensionDays) : null;
        $this->selectedUser->suspend($until, $this->actionReason);
        session()->flash('message', 'Usuario suspendido exitosamente.');
        $this->closeUserModal();
    }

    public function unsuspend()
    {
        if (! $this->selectedUser) return;
        $this->selectedUser->unsuspend();
        session()->flash('message', 'Usuario reactivado exitosamente.');
        $this->closeUserModal();
    }

    public function verifyEmail()
    {
        if (! $this->selectedUser) return;
        if ($this->selectedUser->email_verified_at) {
            session()->flash('error', 'El email ya está verificado.');
            return;
        }
        $this->selectedUser->update(['email_verified_at' => now()]);
        session()->flash('message', 'Email verificado exitosamente.');
        $this->closeUserModal();
    }

    public function delete()
    {
        if (! $this->selectedUser) return;
        if ($this->selectedUser->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            session()->flash('error', 'No se puede eliminar al último administrador del sistema.');
            return;
        }
        $userName = $this->selectedUser->name;
        $this->selectedUser->delete();
        session()->flash('message', "Usuario '{$userName}' eliminado exitosamente.");
        $this->closeUserModal();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }
}

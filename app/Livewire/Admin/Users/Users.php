<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
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
        $query = User::query();

        // Filtro de búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('email', 'ilike', '%'.$this->search.'%');
            });
        }

        // Filtro por rol
        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        // Filtro por estado
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

        // Estadísticas para las tarjetas
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

    public function executeAction()
    {
        if (! $this->selectedUser) {
            return;
        }

        switch ($this->actionType) {
            case 'suspend':
                $until = Carbon::now()->addDays((int) $this->suspensionDays);
                $this->selectedUser->suspend($until, $this->actionReason ?: null);
                $message = 'Usuario suspendido exitosamente.';
                break;
            case 'unsuspend':
                $this->selectedUser->unsuspend();
                $message = 'Usuario reactivado exitosamente.';
                break;
            default:
                $message = 'Acción no válida.';
                break;
        }

        $this->closeUserModal();

        // Mostrar mensaje de éxito
        session()->flash('message', $message);
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

<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileStatus extends Component
{
    public $profileIncomplete = false;

    protected $listeners = ['profile-updated' => 'checkProfileStatus'];

    public function mount()
    {
        $this->checkProfileStatus();
    }

    public function checkProfileStatus()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->profileIncomplete = empty($user->phone) || empty($user->address) || empty($user->city) || empty($user->province);
        } else {
            $this->profileIncomplete = false;
        }
    }

    public function render()
    {
        return view('livewire.profile-status');
    }
}
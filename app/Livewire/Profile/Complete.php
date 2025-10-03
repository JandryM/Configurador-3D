<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Complete extends Component
{
    public string $address = '';

    public string $phone = '';

    public string $province = '';

    public string $city = '';

    public array $cities = [];

    public function mount()
    {
        $this->address = Auth::user()->address ?? '';
        $this->phone = Auth::user()->phone ?? '';
        $this->province = Auth::user()->province ?? '';
        $this->city = Auth::user()->city ?? '';

        if (! empty($this->province)) {
            $this->cities = config('ecuador.provinces')[$this->province] ?? [];
        }
    }

    public function updatedProvince($value)
    {
        if (! empty($value)) {
            $this->cities = config('ecuador.provinces')[$value] ?? [];
            $this->city = '';
        } else {
            $this->cities = [];
        }
    }

    public function completeProfile()
    {
        $validated = $this->validate([
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'province' => ['required', 'string', 'in:'.implode(',', array_keys(config('ecuador.provinces')))],
            'city' => ['required', 'string'],
        ]);

        $user = Auth::user();

        // Usar Query Builder para actualizar
        DB::table('users')->where('id', $user->id)->update($validated);

        session()->forget('needs_profile_completion');

        // Redirigir según el rol del usuario
        $defaultRoute = $user->isAdmin() ? route('admin.dashboard') : route('dashboard');

        return $this->redirectIntended($defaultRoute);
    }

    public function render()
    {
        return view('livewire.profile.complete', [
                        'layout' => 'components.layouts.auth',
        ]);
    }
}

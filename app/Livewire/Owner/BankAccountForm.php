<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;

class BankAccountForm extends Component
{
    public $showModal = false;
    public $bank_name;
    public $account_number;
    public $account_type;
    public $identification;
    public $holder_name;
    public $phone;

    protected $listeners = ['openBankAccountModal' => 'openModal'];

    public function openModal()
    {
        $this->loadAccountData();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['bank_name', 'account_number', 'account_type', 'identification', 'holder_name']);
    }

    public function loadAccountData()
    {
        $owner = Auth::user();
        $account = BankAccount::where('user_id', $owner->id)->first();
        if ($account) {
            $this->bank_name = $account->bank_name;
            $this->account_number = $account->account_number;
            $this->account_type = $account->account_type;
            $this->identification = $account->identification;
            $this->holder_name = $account->holder_name;
        }
        $this->phone = $owner->phone;
    }

    public function save()
    {
        $owner = Auth::user();
        if ($owner->role !== 'owner') {
            abort(403);
        }
        
        $this->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_type' => 'nullable|string|max:255',
            'identification' => 'nullable|string|max:255',
            'holder_name' => 'required|string|max:255',
        ]);
        
        BankAccount::updateOrCreate(
            ['user_id' => $owner->id],
            [
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_type' => $this->account_type,
                'identification' => $this->identification,
                'holder_name' => $this->holder_name,
                'phone' => $this->phone,
            ]
        );
        
        session()->flash('message', 'Cuenta bancaria actualizada correctamente.');
        $this->dispatch('bankAccountUpdated');
        $this->dispatch('cuenta-bancaria-actualizada');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.owner.bank-account-form');
    }
}

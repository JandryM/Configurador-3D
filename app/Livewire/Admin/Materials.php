<?php

namespace App\Livewire\Admin;

use App\Models\Material;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Materials extends Component
{
    use WithPagination;

    public $search = '';
    public $filterByType = 'all'; // all, by_piece, by_unit
    public $showModal = false;
    public $editingMaterial = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterByType' => ['except' => 'all']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterByType()
    {
        $this->resetPage();
    }

    public function createMaterial()
    {
        $this->editingMaterial = null;
        $this->showModal = true;
    }

    public function editMaterial(Material $material)
    {
        $this->editingMaterial = $material;
        $this->showModal = true;
    }

    public function deleteMaterial(Material $material)
    {
        // Verificar si el material está siendo usado en productos
        if ($material->products()->exists()) {
            session()->flash('error', 'No se puede eliminar el material porque está siendo usado en productos.');
            return;
        }

        $material->delete();
        session()->flash('message', 'Material eliminado exitosamente.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingMaterial = null;
    }

    #[On('materialSaved')]
    public function materialSaved()
    {
        $this->closeModal();
        session()->flash('message', 'Material guardado exitosamente.');
    }

    public function render()
    {
        $materials = Material::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterByType !== 'all', function($query) {
                if ($this->filterByType === 'by_piece') {
                    $query->where('is_by_piece', true);
                } else {
                    $query->where('is_by_piece', false);
                }
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.materials', compact('materials'))
               ->layout('layouts.admin');
    }
}

<?php

namespace App\Livewire\Admin\Materials;

use App\Models\Material;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialsIndex extends Component
{
    public function mount()
    {
        logger('Livewire: MaterialsIndex montado correctamente');
        // O puedes usar session()->flash('message', 'Componente montado');
    }
    use WithPagination;

    public $search = '';
    public $filterByType = 'all'; // all, by_piece, by_unit
    public $showCreateModal = false;
    public $showEditModal = false;
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
        $this->showCreateModal = true;
    }

    public function editMaterial(Material $material)
    {
        $this->editingMaterial = $material;
        $this->showEditModal = true;
    }

    public function deleteMaterial(Material $material)
    {
        if ($material->products()->exists()) {
            session()->flash('error', 'No se puede eliminar el material porque está siendo usado en productos.');
            return;
        }
        $material->delete();
        session()->flash('message', 'Material eliminado exitosamente.');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingMaterial = null;
    }

    #[On('materialCreated')]
    public function materialCreated()
    {
        $this->closeCreateModal();
        session()->flash('message', 'Material creado exitosamente.');
    }

    #[On('materialUpdated')]
    public function materialUpdated()
    {
        $this->closeEditModal();
        session()->flash('message', 'Material actualizado exitosamente.');
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
            ->paginate(4);

    return view('livewire.admin.materials.index', compact('materials'))->layout('partials.sidebar');
    }
}

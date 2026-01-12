<?php

namespace App\Livewire\Admin\Materials;

use App\Models\Material;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterByType = 'all'; // all, by_piece, by_unit
    public $showCreateModal = false;
    public $showDeleteModal = false;
    public $showEditModal = false;
    public $editingMaterial = null;
    public $materialToDelete = null;
    public int $paginaMateriales = 1;
    public int $materialesPorPagina = 4;
    public int $totalMateriales = 0;

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

    // Método para abrir el modal de eliminación
    public function confirmDelete($materialId)
    {
        $material = Material::find($materialId);
        
        if ($material) {
            $this->materialToDelete = $material;
            $this->showDeleteModal = true;
        }
    }

    // Método para ejecutar la eliminación
    public function deleteMaterial()
    {
        if (!$this->materialToDelete) {
            return;
        }

        try {
            $material = $this->materialToDelete;
            
            // Verificar si el material está siendo usado en productos
            if ($material->products()->exists()) {
                session()->flash('error', 'No se puede eliminar el material "' . $material->name . '" porque está siendo usado en productos.');
                $this->closeDeleteModal();
                return;
            }
            
            $materialName = $material->name;
            $material->delete();
            
            session()->flash('message', 'Material "' . $materialName . '" eliminado exitosamente.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el material: ' . $e->getMessage());
        }
        
        $this->closeDeleteModal();
    }

    // Método para cerrar el modal de eliminación
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->materialToDelete = null;
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
        $query = Material::query()
            ->with('category')
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
            ->orderBy('name');
        
        $this->totalMateriales = $query->count();
        $materials = $query->skip(($this->paginaMateriales - 1) * $this->materialesPorPagina)
            ->take($this->materialesPorPagina)
            ->get();

        return view('livewire.admin.materials.index', compact('materials'))->layout('partials.sidebar');
    }

    public function actualizarMateriales($pagina = null)
    {
        if ($pagina !== null) {
            $this->paginaMateriales = $pagina;
        }
    }

    public function siguientePaginaMateriales()
    {
        if ($this->paginaMateriales < ceil($this->totalMateriales / $this->materialesPorPagina)) {
            $this->paginaMateriales++;
        }
    }

    public function anteriorPaginaMateriales()
    {
        if ($this->paginaMateriales > 1) {
            $this->paginaMateriales--;
        }
    }
}

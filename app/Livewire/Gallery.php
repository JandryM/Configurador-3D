<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Gallery extends Component
{
    public $selectedCategory = '';
    public $selectedProduct = null;
    public $showModal = false;
    public $showImageZoom = false;
    
    public $categories = [
        '' => 'Todas las categorías',
        'Aluminio y Vidrio' => 'Aluminio y Vidrio',
        'Melamina' => 'Melamina', 
        'Gypsum' => 'Gypsum',
        'Cielo Raso' => 'Cielo Raso',
        'Muebles de Comedor' => 'Muebles de Comedor',
        'Muebles de Sala' => 'Muebles de Sala',
        'Muebles de Oficina' => 'Muebles de Oficina',
        'Muebles Personalizados' => 'Muebles Personalizados'
    ];

    public function getProductsProperty()
    {
        $query = Product::with('category')
                       ->where('is_gallery_visible', true)
                       ->orderBy('created_at', 'desc');
        
        if ($this->selectedCategory) {
            $query->whereHas('category', function($q) {
                $q->where('name', $this->selectedCategory);
            });
        }
        
        return $query->get();
    }

    public function openModal($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->selectedProduct = null;
        $this->showModal = false;
        $this->showImageZoom = false;
    }

    public function openImageZoom()
    {
        $this->showImageZoom = true;
    }

    public function closeImageZoom()
    {
        $this->showImageZoom = false;
    }

    public function render()
    {
        return view('livewire.gallery')
            ->layout('components.layouts.guest');
    }
}
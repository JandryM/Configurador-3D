<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category_id = '';
    public $productType = '';
    public $galleryVisible = '';
    public $perPage = 4;

    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => ''],
        'productType' => ['except' => ''],
        'galleryVisible' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingProductType()
    {
        $this->resetPage();
    }

    public function updatingGalleryVisible()
    {
        $this->resetPage();
    }

    public function toggleGalleryVisibility($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update([
            'is_gallery_visible' => !$product->is_gallery_visible
        ]);

        session()->flash('message', 'Visibilidad del producto actualizada correctamente.');
    }

    public function deleteProduct($productId)
    {
        Product::findOrFail($productId)->delete();
        session()->flash('message', 'Producto eliminado correctamente.');
    }

    public function render()
    {
        $products = Product::query()
            ->with(['creator', 'category'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->when($this->productType, function ($query) {
                $query->where('product_type', $this->productType);
            })
            ->when($this->galleryVisible !== '', function ($query) {
                $query->where('is_gallery_visible', $this->galleryVisible === '1');
            })
            ->latest()
            ->paginate($this->perPage);

        // Estadísticas para las tarjetas
        $totalProducts = Product::count();
        $galleryProducts = Product::where('product_type', 'gallery')->count();
        $customizableProducts = Product::where('product_type', 'customizable')->count();
        $visibleProducts = Product::where('is_gallery_visible', true)->count();

        // Obtener todas las categorías disponibles
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('livewire.admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'galleryProducts' => $galleryProducts,
            'customizableProducts' => $customizableProducts,
            'visibleProducts' => $visibleProducts,
        ])->layout('partials.sidebar');
    }
}

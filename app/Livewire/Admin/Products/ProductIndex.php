<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $productType = '';
    public $galleryVisible = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'productType' => ['except' => ''],
        'galleryVisible' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
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
            ->with('creator')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->productType, function ($query) {
                $query->where('product_type', $this->productType);
            })
            ->when($this->galleryVisible !== '', function ($query) {
                $query->where('is_gallery_visible', $this->galleryVisible === '1');
            })
            ->latest()
            ->paginate($this->perPage);

        $categories = Product::distinct()->pluck('category')->filter();

        return view('livewire.admin.products.product-index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}

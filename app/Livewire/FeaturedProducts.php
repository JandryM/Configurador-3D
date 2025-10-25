<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class FeaturedProducts extends Component
{
    public function getFeaturedProductsProperty()
    {
        return Product::with('category')
                     ->where('is_gallery_visible', true)
                     ->orderBy('created_at', 'desc')
                     ->limit(6)
                     ->get();
    }

    public function render()
    {
        return view('livewire.products.featured-products');
    }
}
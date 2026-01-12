<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $showCreateProductModal = true;
    public $name = '';
    public $description = '';
    public $price = '';
    public $category_id = '';
    public $product_type = 'gallery';
    public $base_cost = '';
    public $base_dimensions = [];
    public $allows_customization = false;
    public $image;
    public $is_gallery_visible = true;

    public $categories = [];

    public $productTypes = [
        'gallery' => 'Producto de Galería (Precio Fijo)',
        'customizable' => 'Producto Personalizable (Costeo por Orden)'
    ];

    public function getRules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:gallery,customizable',
            'image' => 'nullable|image|max:2048',
            'is_gallery_visible' => 'boolean',
            'allows_customization' => 'boolean',
        ];

        if ($this->product_type === 'gallery') {
            $rules['price'] = 'required|numeric|min:0';
        } else {
            $rules['base_cost'] = 'required|numeric|min:0';
            $rules['base_dimensions'] = 'required|array';
        }

        return $rules;
    }
    public function mount()
    {
        $this->categories = \App\Models\Category::orderBy('name')->get();
    }

    protected $messages = [
        'name.required' => 'El nombre del producto es obligatorio.',
        'description.required' => 'La descripción del producto es obligatoria.',
        'price.required' => 'El precio del producto es obligatorio.',
        'price.numeric' => 'El precio debe ser un número válido.',
        'price.min' => 'El precio debe ser mayor o igual a 0.',
        'base_cost.required' => 'El costo base es obligatorio para productos personalizables.',
        'base_cost.numeric' => 'El costo base debe ser un número válido.',
        'base_cost.min' => 'El costo base debe ser mayor o igual a 0.',
        'base_dimensions.required' => 'Las dimensiones base son obligatorias para productos personalizables.',
        'category.required' => 'La categoría es obligatoria.',
        'product_type.required' => 'El tipo de producto es obligatorio.',
        'image.image' => 'El archivo debe ser una imagen.',
        'image.max' => 'La imagen no debe ser mayor a 2MB.',
    ];

    public function updated($propertyName)
    {
        // Actualizar visibilidad y personalización basado en el tipo
        if ($propertyName === 'product_type') {
            if ($this->product_type === 'gallery') {
                $this->is_gallery_visible = true;
                $this->allows_customization = false;
                $this->base_cost = '';
                $this->base_dimensions = [];
            } else {
                // Mantener visible en galería también para productos personalizables
                $this->is_gallery_visible = true;
                $this->allows_customization = true;
                $this->price = '';
            }
        }

        $this->validateOnly($propertyName, $this->getRules());
    }

    public function save()
    {
        $this->validate($this->getRules());

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $slug = $this->generateUniqueSlug($this->name);
        $productData = [
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'product_type' => $this->product_type,
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'is_gallery_visible' => $this->is_gallery_visible,
            'allows_customization' => $this->allows_customization,
        ];

        if ($this->product_type === 'gallery') {
            $productData['price'] = $this->price;
        } else {
            $productData['base_cost'] = $this->base_cost;
            $productData['base_dimensions'] = $this->base_dimensions;
        }

        $product = Product::create($productData);

        $this->reset();
        $this->dispatch('productCreated');
        session()->flash('message', 'Producto creado exitosamente.');
    }

    public function cancel()
    {
        $this->reset();
        $this->dispatch('closeCreateProductModal');
    }

        /**
     * Genera un slug único para el producto
     */
    private function generateUniqueSlug($name)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }

    public function render()
    {
        return view('livewire.admin.products.create');
    }
}

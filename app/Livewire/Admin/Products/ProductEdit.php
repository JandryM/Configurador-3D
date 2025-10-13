<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductEdit extends Component
{
    use WithFileUploads;

    public Product $product;
    
    public $name = '';
    public $description = '';
    public $price = '';
    public $category_id = '';
    public $image;
    public $currentImage;
    public $is_gallery_visible = true;

    public $categories = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:2048',
        'is_gallery_visible' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'El nombre del producto es obligatorio.',
        'description.required' => 'La descripción del producto es obligatoria.',
        'price.required' => 'El precio del producto es obligatorio.',
        'price.numeric' => 'El precio debe ser un número válido.',
        'price.min' => 'El precio debe ser mayor o igual a 0.',
        'category_id.required' => 'La categoría es obligatoria.',
        'category_id.exists' => 'La categoría seleccionada no existe.',
        'image.image' => 'El archivo debe ser una imagen.',
        'image.max' => 'La imagen no debe ser mayor a 2MB.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->currentImage = $product->image;
        $this->is_gallery_visible = $product->is_gallery_visible;
        $this->categories = \App\Models\Category::orderBy('name')->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function removeCurrentImage()
    {
        $this->currentImage = null;
    }

    public function save()
    {
        $this->validate();

        $imagePath = $this->currentImage;

        // Si hay una nueva imagen, eliminar la anterior y guardar la nueva
        if ($this->image) {
            if ($this->product->image) {
                Storage::disk('public')->delete($this->product->image);
            }
            $imagePath = $this->image->store('products', 'public');
        }
        // Si se removió la imagen actual y no hay nueva imagen
        elseif (!$this->currentImage && $this->product->image) {
            Storage::disk('public')->delete($this->product->image);
            $imagePath = null;
        }

        $this->product->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'image' => $imagePath,
            'is_gallery_visible' => $this->is_gallery_visible,
        ]);

        session()->flash('message', 'Producto actualizado exitosamente.');
        
        return redirect()->route('admin.products.index');
    }

    public function cancel()
    {
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.products.product-edit');
    }
}

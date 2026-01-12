<?php

namespace App\Livewire\Admin\Products;

use App\Livewire\Traits\WithCustomPagination;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductIndex extends Component
{
    use WithCustomPagination, WithFileUploads;

    // Filtros y búsqueda
    public $search = '';
    public $category_id = '';
    public $productType = '';
    public $galleryVisible = '';

    // Modal de creación
    public $showCreateProductModal = false;
    public $name = '';
    public $description = '';
    public $price = '';
    public $new_category_id = '';
    public $image;
    public $is_gallery_visible = true;

    // Modal de edición
    public $showEditProductModal = false;
    public $edit_product_id = null;
    public $edit_name = '';
    public $edit_description = '';
    public $edit_price = '';
    public $edit_category_id = '';
    public $edit_image;
    public $edit_current_image;
    public $edit_is_gallery_visible = true;

    // Modales de confirmación de visibilidad
    public $showVisibilityConfirmModal = false;
    public $pendingVisibilityProductId = null;
    public $pendingVisibilityAction = null; // 'gallery' o 'customization'
    public $pendingVisibilityValue = null; // true o false

    // Modal de confirmación de eliminación
    public $showDeleteConfirmModal = false;
    public $pendingDeleteProductId = null;
    public $pendingDeleteProductName = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => ''],
        'productType' => ['except' => ''],
        'galleryVisible' => ['except' => ''],
    ];

    public function mount()
    {
        // Inicializar paginación con 5 elementos por página
        $this->perPage = 5;
    }

    // ============================================
    // MÉTODOS DE MODAL DE CREACIÓN
    // ============================================

    public function openCreateProductModal()
    {
        $this->resetProductForm();
        $this->showCreateProductModal = true;
    }

    public function closeCreateProductModal()
    {
        $this->showCreateProductModal = false;
        $this->resetProductForm();
    }

    public function resetProductForm()
    {
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->new_category_id = '';
        $this->image = null;
        $this->is_gallery_visible = true;
        $this->resetValidation();
    }

    public function saveProduct()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'new_category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:99999.99|regex:/^\d{1,5}(\.\d{1,2})?$/',
            'image' => 'required|mimes:jpg,jpeg,png,gif,bmp,svg,webp,avif|max:2048',
            'is_gallery_visible' => 'required|boolean',
        ];

        try {
            $validated = $this->validate($rules);
        } catch (\Exception $e) {
            // Si hay un error con el archivo (formato no soportado, corrupto, etc.)
            session()->flash('error', 'El archivo de imagen no es válido o no está en un formato soportado. Por favor, selecciona una imagen válida (JPG, PNG, GIF, etc.).');
            return;
        }

        // Formatear el precio para asegurar 2 decimales
        $this->price = number_format((float)$this->price, 2, '.', '');

        $imagePath = $this->image ? $this->image->store('products', 'public') : null;

        $slug = $this->generateUniqueSlug($this->name);
        $productData = [
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'category_id' => $this->new_category_id,
            'product_type' => 'gallery',
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'is_gallery_visible' => $this->is_gallery_visible,
            'allows_customization' => false,
            'price' => $this->price,
        ];

        Product::create($productData);

        $this->closeCreateProductModal();
        session()->flash('message', 'Producto creado exitosamente.');
    }

    // ============================================
    // MÉTODOS DE MODAL DE EDICIÓN
    // ============================================

    public function openEditProductModal($productId)
    {
        $product = Product::findOrFail($productId);
        $this->edit_product_id = $product->id;
        $this->edit_name = $product->name;
        $this->edit_description = $product->description;
        $this->edit_price = $product->price;
        $this->edit_category_id = $product->category_id;
        $this->edit_current_image = $product->image;
        $this->edit_is_gallery_visible = $product->is_gallery_visible;
        $this->edit_image = null;
        $this->resetValidation();
        $this->showEditProductModal = true;
    }

    public function closeEditProductModal()
    {
        $this->showEditProductModal = false;
        $this->resetEditProductForm();
    }

    public function resetEditProductForm()
    {
        $this->edit_product_id = null;
        $this->edit_name = '';
        $this->edit_description = '';
        $this->edit_price = '';
        $this->edit_category_id = '';
        $this->edit_image = null;
        $this->edit_current_image = null;
        $this->edit_is_gallery_visible = true;
        $this->resetValidation();
    }

    public function saveEditProduct()
    {
        $rules = [
            'edit_name' => 'required|string|max:255',
            'edit_description' => 'required|string',
            'edit_price' => 'required|numeric|min:0|max:99999.99|regex:/^\d{1,5}(\.\d{1,2})?$/',
            'edit_category_id' => 'required|exists:categories,id',
            'edit_is_gallery_visible' => 'required|boolean',
        ];

        // Solo requerir imagen si no hay imagen previa
        if (!$this->edit_current_image) {
            $rules['edit_image'] = 'required|mimes:jpg,jpeg,png,gif,bmp,svg,webp,avif|max:2048';
        } elseif ($this->edit_image) {
            $rules['edit_image'] = 'nullable|mimes:jpg,jpeg,png,gif,bmp,svg,webp,avif|max:2048';
        }

        try {
            $validated = $this->validate($rules);
        } catch (\Exception $e) {
            // Si hay un error con el archivo (formato no soportado, corrupto, etc.)
            session()->flash('error', 'El archivo de imagen no es válido o no está en un formato soportado. Por favor, selecciona una imagen válida (JPG, PNG, GIF, etc.).');
            return;
        }

        // Formatear el precio para asegurar 2 decimales
        $this->edit_price = number_format((float)$this->edit_price, 2, '.', '');

        $product = Product::findOrFail($this->edit_product_id);
        $imagePath = $this->edit_current_image;

        // Si hay una nueva imagen, eliminar la anterior y guardar la nueva
        if ($this->edit_image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $this->edit_image->store('products', 'public');
        } elseif (!$this->edit_current_image && $product->image) {
            Storage::disk('public')->delete($product->image);
            $imagePath = null;
        }

        $product->update([
            'name' => $this->edit_name,
            'description' => $this->edit_description,
            'price' => $this->edit_price,
            'category_id' => $this->edit_category_id,
            'image' => $imagePath,
            'is_gallery_visible' => $this->edit_is_gallery_visible,
        ]);

        $this->closeEditProductModal();
        session()->flash('message', 'Producto actualizado exitosamente.');
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function removeEditImage()
    {
        $this->edit_image = null;
    }

    // ============================================
    // ACCIONES EN TABLA
    // ============================================

    public function toggleGalleryVisibility($productId)
    {
        // Limpiar valores previos
        $this->reset(['pendingVisibilityProductId', 'pendingVisibilityAction', 'pendingVisibilityValue']);
        
        $product = Product::findOrFail($productId);
        $this->pendingVisibilityProductId = $productId;
        $this->pendingVisibilityAction = 'gallery';
        $this->pendingVisibilityValue = !$product->is_gallery_visible;
        $this->showVisibilityConfirmModal = true;
    }

    public function toggleAllowsCustomization($productId)
    {
        // Limpiar valores previos
        $this->reset(['pendingVisibilityProductId', 'pendingVisibilityAction', 'pendingVisibilityValue']);
        
        $product = Product::findOrFail($productId);
        $this->pendingVisibilityProductId = $productId;
        $this->pendingVisibilityAction = 'customization';
        $this->pendingVisibilityValue = !$product->allows_customization;
        $this->showVisibilityConfirmModal = true;
    }

    public function confirmVisibilityChange()
    {
        $product = Product::findOrFail($this->pendingVisibilityProductId);
        
        if ($this->pendingVisibilityAction === 'gallery') {
            $product->is_gallery_visible = $this->pendingVisibilityValue;
            $message = $this->pendingVisibilityValue 
                ? 'Producto ahora visible en galería' 
                : 'Producto ocultado de galería';
        } else {
            $product->allows_customization = $this->pendingVisibilityValue;
            $message = $this->pendingVisibilityValue 
                ? 'Producto ahora disponible para personalización' 
                : 'Producto ocultado de personalización';
        }
        
        $product->save();
        $this->closeVisibilityConfirmModal();
        session()->flash('message', $message);
    }

    public function closeVisibilityConfirmModal()
    {
        $this->showVisibilityConfirmModal = false;
        $this->pendingVisibilityProductId = null;
        $this->pendingVisibilityAction = null;
        $this->pendingVisibilityValue = null;
    }

    public function openDeleteConfirmModal($productId)
    {
        $product = Product::findOrFail($productId);
        $this->pendingDeleteProductId = $productId;
        $this->pendingDeleteProductName = $product->name;
        $this->showDeleteConfirmModal = true;
    }

    public function confirmDeleteProduct()
    {
        $product = Product::findOrFail($this->pendingDeleteProductId);
        
        // Eliminar imagen del storage si existe
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        $this->closeDeleteConfirmModal();
        session()->flash('message', 'Producto eliminado correctamente.');
    }

    public function closeDeleteConfirmModal()
    {
        $this->showDeleteConfirmModal = false;
        $this->pendingDeleteProductId = null;
        $this->pendingDeleteProductName = null;
    }

    // ============================================
    // FILTROS Y BÚSQUEDA
    // ============================================

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

    // ============================================
    // RENDER
    // ============================================

    public function render()
    {
        $query = Product::query()
            ->with(['creator', 'category'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query) {
                // Obtener la categoría seleccionada y sus hijas
                $category = \App\Models\Category::find($this->category_id);
                if ($category) {
                    $categoryIds = array_merge([$this->category_id], $category->getAllChildrenIds());
                    $query->whereIn('category_id', $categoryIds);
                }
            })
            ->when($this->productType, function ($query) {
                $query->where('product_type', $this->productType);
            })
            ->when($this->galleryVisible !== '', function ($query) {
                // Filtrar visibilidad solo para productos de galería
                if ($this->productType === 'gallery' || $this->productType === '') {
                    $query->where(function($q) {
                        if ($this->productType === 'gallery') {
                            // Solo productos de galería con la visibilidad seleccionada
                            $q->where('product_type', 'gallery')
                              ->where('is_gallery_visible', $this->galleryVisible === '1');
                        } else {
                            // Incluir productos de galería con la visibilidad seleccionada
                            // y todos los productos customizables
                            $q->where(function($subQ) {
                                $subQ->where('product_type', 'gallery')
                                     ->where('is_gallery_visible', $this->galleryVisible === '1');
                            })->orWhere('product_type', 'customizable');
                        }
                    });
                }
                // Si el tipo es customizable, el filtro de visibilidad no aplica
            })
            ->latest();
        
        // Guardar el total para la paginación
        $this->total = $query->count();
        $products = $query->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

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

    // ============================================
    // MÉTODOS PRIVADOS
    // ============================================

    private function generateUniqueSlug($name)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }
}

<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product3DManager extends Component
{
    use WithFileUploads;

    public Product $product;
    public $model3DFile;
    public $textureFiles = [];
    public $model_scale = 1.0;
    public $model_3d_settings = [];
    public $model_3d_materials = [];
    
    // Configuraciones del visor
    public $viewerSettings = [
        'backgroundColor' => '#f0f0f0',
        'enableControls' => true,
        'showWireframe' => false,
        'enableShadows' => true,
        'showGrid' => false,
        'ambientLightIntensity' => 0.6,
        'directionalLightIntensity' => 0.8,
        'cameraPosition' => ['x' => 5, 'y' => 5, 'z' => 5]
    ];

    public $supportedFormats = [
        'glb' => 'GL Transmission Format Binary (.glb)',
        'gltf' => 'GL Transmission Format (.gltf)',
        'obj' => 'Wavefront OBJ (.obj)'
    ];

    protected $rules = [
        'model3DFile' => 'nullable|file|mimes:glb,gltf,obj|max:51200', // 50MB max
        'textureFiles.*' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240', // 10MB max per texture
        'model_scale' => 'required|numeric|min:0.0001|max:1000',
        'viewerSettings.backgroundColor' => 'required|string',
        'viewerSettings.enableControls' => 'boolean',
        'viewerSettings.showWireframe' => 'boolean',
        'viewerSettings.enableShadows' => 'boolean',
        'viewerSettings.showGrid' => 'boolean',
        'viewerSettings.ambientLightIntensity' => 'required|numeric|min:0|max:2',
        'viewerSettings.directionalLightIntensity' => 'required|numeric|min:0|max:2',
    ];

    protected $messages = [
        'model3DFile.mimes' => 'El archivo debe ser de tipo GLB, GLTF o OBJ.',
        'model3DFile.max' => 'El archivo no puede exceder 50MB.',
        'textureFiles.*.mimes' => 'Las texturas deben ser archivos de imagen (JPG, PNG, WebP).',
        'textureFiles.*.max' => 'Cada textura no puede exceder 10MB.',
        'model_scale.required' => 'La escala del modelo es obligatoria.',
        'model_scale.min' => 'La escala debe ser mayor a 0.0001.',
        'model_scale.max' => 'La escala no puede exceder 1000.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->model_scale = $product->model_scale ?? 1.0;
        
        if ($product->model_3d_settings) {
            $this->viewerSettings = array_merge($this->viewerSettings, $product->model_3d_settings);
        }
        
        if ($product->model_3d_materials) {
            $this->model_3d_materials = $product->model_3d_materials;
        }
    }

    public function save3DModel()
    {
        $this->validate();

        try {
            $updates = [
                'model_scale' => $this->model_scale,
                'model_3d_settings' => $this->viewerSettings,
                'model_3d_materials' => $this->model_3d_materials,
            ];

            // Manejar archivo de modelo 3D
            if ($this->model3DFile) {
                // Eliminar archivo anterior si existe
                if ($this->product->model_3d_file) {
                    Storage::disk('public')->delete($this->product->model_3d_file);
                }

                // Guardar nuevo archivo
                $filename = Str::slug($this->product->name) . '_' . time() . '.' . $this->model3DFile->getClientOriginalExtension();
                $path = $this->model3DFile->storeAs('models/3d', $filename, 'public');
                
                $updates['model_3d_file'] = $path;
                $updates['has_3d_model'] = true;
            }

            // Manejar texturas
            if (!empty($this->textureFiles)) {
                $texturePaths = [];
                
                foreach ($this->textureFiles as $index => $textureFile) {
                    if ($textureFile) {
                        $textureFilename = Str::slug($this->product->name) . '_texture_' . $index . '_' . time() . '.' . $textureFile->getClientOriginalExtension();
                        $texturePath = $textureFile->storeAs('models/textures', $textureFilename, 'public');
                        $texturePaths[] = $texturePath;
                    }
                }
                
                // Combinar con texturas existentes
                $existingTextures = $this->product->model_3d_textures ?? [];
                $updates['model_3d_textures'] = array_merge($existingTextures, $texturePaths);
            }

            $this->product->update($updates);

            $this->model3DFile = null;
            $this->textureFiles = [];

            session()->flash('message', 'Modelo 3D guardado exitosamente.');
            $this->dispatch('model3DUpdated');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el modelo 3D: ' . $e->getMessage());
        }
    }

    public function remove3DModel()
    {
        try {
            // Eliminar archivos
            if ($this->product->model_3d_file) {
                Storage::disk('public')->delete($this->product->model_3d_file);
            }
            
            if ($this->product->model_3d_textures) {
                foreach ($this->product->model_3d_textures as $texture) {
                    Storage::disk('public')->delete($texture);
                }
            }

            // Limpiar campos
            $this->product->update([
                'model_3d_file' => null,
                'model_3d_textures' => null,
                'model_3d_materials' => null,
                'model_3d_settings' => null,
                'has_3d_model' => false,
                'model_scale' => 1.0
            ]);

            // Reset component state
            $this->model_scale = 1.0;
            $this->viewerSettings = [
                'backgroundColor' => '#f0f0f0',
                'enableControls' => true,
                'showWireframe' => false,
                'enableShadows' => true,
                'showGrid' => false,
                'ambientLightIntensity' => 0.6,
                'directionalLightIntensity' => 0.8,
                'cameraPosition' => ['x' => 5, 'y' => 5, 'z' => 5]
            ];
            $this->model_3d_materials = [];

            session()->flash('message', 'Modelo 3D eliminado exitosamente.');
            $this->dispatch('model3DRemoved');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el modelo 3D: ' . $e->getMessage());
        }
    }

    public function removeTexture($index)
    {
        try {
            $textures = $this->product->model_3d_textures ?? [];
            
            if (isset($textures[$index])) {
                // Eliminar archivo
                Storage::disk('public')->delete($textures[$index]);
                
                // Remover de array
                unset($textures[$index]);
                $textures = array_values($textures); // Reindexar
                
                $this->product->update(['model_3d_textures' => $textures]);
                
                session()->flash('message', 'Textura eliminada exitosamente.');
                $this->dispatch('textureRemoved');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la textura: ' . $e->getMessage());
        }
    }

    public function addTextureSlot()
    {
        $this->textureFiles[] = null;
    }

    public function removeTextureSlot($index)
    {
        unset($this->textureFiles[$index]);
        $this->textureFiles = array_values($this->textureFiles);
    }

    public function getModelFileInfoProperty()
    {
        if (!$this->product->model_3d_file) {
            return null;
        }

        $path = storage_path('app/public/' . $this->product->model_3d_file);
        
        return [
            'name' => basename($this->product->model_3d_file),
            'size' => file_exists($path) ? filesize($path) : 0,
            'url' => Storage::url($this->product->model_3d_file),
            'extension' => pathinfo($this->product->model_3d_file, PATHINFO_EXTENSION)
        ];
    }

    public function render()
    {
        return view('livewire.admin.products.product3-d-manager');
    }
}

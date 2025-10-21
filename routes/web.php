<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Ruta para la página de proformas
Route::get('/proforma', function () {
    return view('proforma');
})->name('proforma');

// Ruta pública para la galería
Route::get('/galeria', \App\Livewire\Gallery::class)->name('galeria');

// Ruta pública para el configurador 3D
Route::get('/configurador/{product}', \App\Livewire\ProductConfigurator::class)->name('configurador');

// Rutas para autenticación con Google
Route::get('/auth/google', [App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback']);

// Rutas para completar perfil (requiere autenticación pero no verificación)
Route::get('/profile/complete', App\Livewire\Profile\Complete::class)
    ->middleware(['auth'])
    ->name('profile.complete');

// Todas las rutas protegidas que requieren autenticación y verificación de email
Route::middleware(['auth', 'verified', 'account.active'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Rutas de configuración
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    // Otras rutas protegidas...
});

// Rutas específicas para administradores
Route::middleware(['auth', 'verified', 'account.active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard de administrador
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestión de usuarios
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');
    
    // Acciones de usuarios
    Route::post('/users/{user}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/unsuspend', [App\Http\Controllers\Admin\UserController::class, 'unsuspend'])->name('users.unsuspend');
    Route::post('/users/{user}/verify-email', [App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('users.verify-email');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Gestión de productos
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('products.index');

    // Crear producto
    Route::get('/products/create', function () {
        return view('admin.products.create');
    })->name('products.create');

    // Almacenar producto
    Route::post('/products', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'product_type' => 'required|in:gallery,customizable',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'height' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $validated['user_id'] = auth()->id();
        $validated['height'] = $request->input('height');
        $validated['width'] = $request->input('width');

        \App\Models\Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente.');
    })->name('products.store');

    // Editar producto
    Route::get('/products/{product}/edit', function (\App\Models\Product $product) {
        return view('admin.products.edit', compact('product'));
    })->name('products.edit');

    // Actualizar producto
    Route::put('/products/{product}', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_gallery_visible' => 'boolean',
            'height' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado exitosamente.');
    })->name('products.update');

    // Gestión de materiales
    Route::get('/materials', function () {
        return view('admin.materials.index');
    })->name('materials.index');

    // Gestión de proformas
    Route::get('/proformas', function () {
        return view('admin.proformas.index');
    })->name('proformas.index');

    // Obtener dimensiones de una categoría
    Route::get('/categories/{category}/dimensions', function (\App\Models\Category $category) {
        // Simulación de dimensiones asociadas a la categoría
        $dimensions = match ($category->id) {
            1 => [
                ['name' => 'height', 'label' => 'Alto'],
                ['name' => 'width', 'label' => 'Ancho']
            ],
            2 => [
                ['name' => 'height', 'label' => 'Alto'],
                ['name' => 'width', 'label' => 'Ancho'],
                ['name' => 'depth', 'label' => 'Profundidad']
            ],
            default => []
        };

        return response()->json(['dimensions' => $dimensions], 200);
    });

    // Alternar visibilidad de producto en galería
    Route::patch('/products/{product}/toggle-visibility', function (\App\Models\Product $product) {
        $product->update(['is_gallery_visible' => !$product->is_gallery_visible]);
        return redirect()->route('admin.products.index')->with('success', 'Visibilidad del producto actualizada.');
    })->name('products.toggleVisibility');
});

require __DIR__.'/auth.php';

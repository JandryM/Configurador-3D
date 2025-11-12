<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;


// -------------------
// Rutas públicas
// -------------------
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/auth/google', [App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback']);

Route::get('/configurador/{product}', \App\Livewire\ProductConfigurator::class)->name('configurador');
Route::get('/galeria', \App\Livewire\Gallery::class)->name('galeria');
Route::get('/proforma', function () {
    return view('proforma');
})->name('proforma');

// -------------------
// Rutas autenticadas y verificadas
// -------------------
Route::middleware(['auth', 'verified', 'account.active'])->group(function () {
    // Dashboard para todos los roles
    Route::get('/dashboard', function () {
        return view('pages.users.dashboard');
    })->name('dashboard');

    // Configuración
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
});

// -------------------
// Rutas de administrador
// -------------------
Route::middleware(['auth', 'verified', 'account.active', 'admin.seller'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard de administrador
    Route::get('/dashboard', function () {
        return view('pages.users.dashboard');
    })->name('dashboard');

    // Gestión de materiales
    //Route::get('/materials', function () {
    //   return view('pages.admin.materials');
    //})->name('materials.index');

    Route::get('/materials', \App\Livewire\Admin\Materials\MaterialsIndex::class)->name('materials.index');
    
    // Configuración de costos globales
    Route::get('/cost-settings', \App\Livewire\Admin\GlobalCostSettings::class)->name('cost-settings');

    Route::get('/product-cost-settings', \App\Livewire\Admin\ProductCostSettings::class)->name('product-cost-settings');
    
    // Gestión de proformas
    Route::get('/proformas', \App\Livewire\Admin\ProformasTable::class)->name('proformas.index');

    // Gestión de órdenes
    Route::get('/orders', \App\Livewire\Admin\OrdersTable::class)->name('orders.index');

    // Gestión de productos
    Route::get('/products', \App\Livewire\Admin\Products\ProductIndex::class)->name('products.index');
    Route::middleware(['admin.owner'])->group(function () {
        Route::get('/products/create', \App\Livewire\Admin\Products\ProductCreate::class)->name('products.create');
        Route::post('/products', \App\Livewire\Admin\Products\ProductCreate::class)->name('products.store');
        Route::get('/products/{product}/edit', \App\Livewire\Admin\Products\ProductEdit::class)->name('products.edit');
        Route::get('/products/{product}/3d-model', \App\Livewire\Admin\Products\Product3DManager::class)->name('products.3d-model');
    });
    
    // Gestión de usuarios
    Route::get('/users', \App\Livewire\Admin\Users\UsersIndex::class)->name('users.index');
    Route::get('/users/create', \App\Livewire\Admin\Users\UsersCreate::class)->name('users.create');
    Route::post('/users', \App\Livewire\Admin\Users\UsersCreate::class)->name('users.store');
    Route::get('/users/{user}/edit', \App\Livewire\Admin\Users\UsersEdit::class)->name('users.edit');
});

// -------------------
// Rutas de autenticación
// -------------------
require __DIR__.'/auth.php';

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

    // Gestión de materiales
    Route::get('/materials', function () {
        return view('admin.materials.index');
    })->name('materials.index');

    // Gestión de proformas
    Route::get('/proformas', function () {
        return view('admin.proformas.index');
    })->name('proformas.index');

    // Gestión de materiales  
    Route::get('/materials', function () {
        return view('admin.materials.index');
    })->name('materials.index');

    // Gestión de proformas
    Route::get('/proformas', function () {
        return view('admin.proformas.index');
    })->name('proformas.index');
});

require __DIR__.'/auth.php';

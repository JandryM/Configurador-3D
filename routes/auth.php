<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

Route::middleware(['auth'])->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }
        
        $request->user()->sendEmailVerificationNotification();
        
        return response()->json(['message' => 'Verification link sent!']);
    })->middleware('throttle:6,1')->name('verification.send');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});

// Logout route - accesible para todos los usuarios autenticados
Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');

return [
    'verification' => [
        'expire' => 60, // minutos
        'throttle' => 6, // número máximo de envíos
    ],
];

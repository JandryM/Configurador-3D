<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Suspender un usuario
     */
    public function suspend(Request $request, User $user)
    {
        // Validar que no sea el último administrador
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede suspender al último administrador del sistema.'
            ], 422);
        }

        // Validar datos de suspensión
        $request->validate([
            'duration' => 'required|in:1,7,30,90,365,permanent',
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Determinar fecha de fin de suspensión
            $suspendedUntil = null;
            if ($request->duration !== 'permanent') {
                $suspendedUntil = now()->addDays((int) $request->duration);
            }

            // Suspender usuario usando el método del modelo
            $user->suspend($suspendedUntil, $request->reason);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario suspendido exitosamente.',
                'user' => [
                    'id' => $user->id,
                    'status' => $user->getAccountStatus(),
                    'status_color' => $user->getAccountStatusColor(),
                    'suspended_until' => $user->suspended_until?->format('d/m/Y'),
                    'suspension_reason' => $user->suspension_reason
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al suspender el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reactivar un usuario suspendido
     */
    public function unsuspend(User $user)
    {
        try {
            DB::beginTransaction();

            // Reactivar usuario usando el método del modelo
            $user->unsuspend();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario reactivado exitosamente.',
                'user' => [
                    'id' => $user->id,
                    'status' => $user->getAccountStatus(),
                    'status_color' => $user->getAccountStatusColor()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al reactivar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar email de usuario manualmente
     */
    public function verifyEmail(User $user)
    {
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'El email ya está verificado.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user->update(['email_verified_at' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Email verificado exitosamente.',
                'user' => [
                    'id' => $user->id,
                    'status' => $user->getAccountStatus(),
                    'status_color' => $user->getAccountStatusColor(),
                    'email_verified' => true
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(User $user)
    {
        // Validar que no sea el último administrador
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar al último administrador del sistema.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Guardar información para respuesta
            $userName = $user->name;
            
            // Eliminar usuario
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Usuario '{$userName}' eliminado exitosamente.",
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:owner,seller',
        ]);

        $currentUser = Auth::user();

        // Validar permisos según el rol del usuario autenticado
        if (
            ($currentUser->isOwner() && $request->role !== User::ROLE_SELLER) ||
            (!$currentUser->isAdmin() && !$currentUser->isOwner())
        ) {
            return redirect()->back()->withErrors(['role' => 'No tienes permiso para asignar este rol.']);
        }

        // Validar que no haya restricciones para el rol de propietario
        if ($request->role === User::ROLE_OWNER && !$currentUser->isAdmin()) {
            return redirect()->back()->withErrors(['role' => 'Solo un administrador puede asignar el rol de propietario.']);
        }

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    }
}
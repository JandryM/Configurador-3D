<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
}
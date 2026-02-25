<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GlobalCostSetting extends Model
{
    protected $fillable = [
        'user_id',
        'indirect_cost_percentage',
        'valid_from',
        'valid_until',
        'is_locked',
        'edit_attempts',
    ];
    /**
     * Relación: GlobalCostSetting pertenece a un User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'indirect_cost_percentage' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_locked' => 'boolean',
        'edit_attempts' => 'integer',
    ];

    /**
     * Scope para obtener la configuración vigente actual
     * Devuelve la más reciente aunque esté expirada (se usa hasta crear una nueva)
     */
    public function scopeCurrent($query)
    {
        // Simplemente devuelve la configuración más reciente
        return $query->orderByDesc('id');
    }

    /**
     * Verifica si está próximo a expirar (2-3 días antes)
     */
    public function isExpiringSoon($days = 3)
    {
        if (!$this->valid_until) {
            return false;
        }
        
        $daysUntilExpiration = now()->diffInDays($this->valid_until, false);
        return $daysUntilExpiration >= 0 && $daysUntilExpiration <= $days;
    }

    /**
     * Verifica si ya expiró
     */
    public function isExpired()
    {
        if (!$this->valid_until) {
            return false;
        }
        
        return now()->isAfter($this->valid_until);
    }

    /**
     * Verifica si está vigente
     */
    public function isValid()
    {
        $today = now();
        
        if ($this->valid_from && $today->isBefore($this->valid_from)) {
            return false;
        }
        
        if ($this->valid_until && $today->isAfter($this->valid_until)) {
            return false;
        }
        
        return true;
    }

    /**
     * Verifica si puede ser editado (máximo 2 intentos en 1 minuto desde creación/última edición)
     */
    public function canBeEdited()
    {
        // Si no está bloqueado, se puede editar libremente
        if (!$this->is_locked) {
            return true;
        }

        // Si no hay created_at, no permitir
        if (!$this->created_at) {
            return false;
        }

        $editWindowEnd = $this->created_at->copy()->addHours(24);
        $now = now();
        
        // Si pasó el tiempo límite desde la creación, ya NO se puede editar
        if ($now->greaterThan($editWindowEnd)) {
            return false;
        }

        // Dentro del tiempo límite, verificar intentos restantes
        return $this->edit_attempts < 2;
    }

    /**
     * Registra un intento de edición
     */
    public function recordEdit()
    {
        $this->edit_attempts += 1;
        $this->save();
    }

    /**
     * Obtiene el tiempo restante de la ventana de edición
     */
    public function getTimeUntilEditWindowCloses()
    {
        if (!$this->created_at) {
            return null;
        }

        $editWindowEnd = $this->created_at->copy()->addHours(24);
        $now = now();

        if ($now->greaterThanOrEqualTo($editWindowEnd)) {
            return null; // Ventana cerrada
        }

        return $now->diffForHumans($editWindowEnd, true);
    }
}

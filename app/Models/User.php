<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;


/**
 * Modelo User - Gestiona los usuarios del sistema CYH
 * 
 * Este modelo representa a los usuarios que pueden acceder al sistema,
 * implementando autenticación, autorización y control de acceso al panel
 * de Filament. Extiende el modelo de autenticación de Laravel y
 * implementa funcionalidades específicas de Filament.
 * 
 * @property int $id Identificador único del usuario
 * @property string $name Nombre completo del usuario
 * @property string $email Dirección de correo electrónico (única)
 * @property string $password Contraseña hasheada del usuario
 * @property string|null $email_verified_at Fecha de verificación del email
 * @property string|null $remember_token Token para "recordar sesión"
 * @property \Carbon\Carbon $created_at Fecha de creación del usuario
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles Roles asignados al usuario
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions Permisos directos del usuario
 * 
 * @since 1.0.0
 * @author Sistema CYH
 */
class User extends Authenticatable implements FilamentUser
{
    /**
     * Traits utilizados por el modelo User
     * 
     * @uses \Laravel\Sanctum\HasApiTokens Para autenticación API con tokens
     * @uses \Illuminate\Database\Eloquent\Factories\HasFactory Para factories de testing
     * @uses \Illuminate\Notifications\Notifiable Para sistema de notificaciones
     * @uses \Spatie\Permission\Traits\HasRoles Para gestión de roles y permisos
     * @uses \BezhanSalleh\FilamentShield\Traits\HasPanelShield Para control de acceso al panel
     */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * Los atributos que son asignables masivamente.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',      // Nombre completo del usuario
        'email',     // Dirección de correo electrónico
        'password',  // Contraseña del usuario
    ];

    /**
     * Los atributos que deben ocultarse durante la serialización.
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password',        // Contraseña hasheada (nunca se expone)
        'remember_token',  // Token de "recordar sesión" (seguridad)
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',  // Convierte a instancia de Carbon
        'password' => 'hashed',             // Aplica hash automáticamente
    ];
    /**
     * Determina si el usuario puede acceder al panel de Filament.
     * 
     * Este método implementa la interfaz FilamentUser y controla
     * el acceso al panel administrativo basándose en los roles
     * asignados al usuario.
     * 
     * @param \Filament\Panel $panel El panel al que se intenta acceder
     * @return bool True si el usuario tiene acceso, false en caso contrario
     * 
     * @example
     * // Verificar acceso de un usuario
     * $user = User::find(1);
     * if ($user->canAccessPanel($panel)) {
     *     // Usuario tiene acceso al panel
     * }
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Permitir acceso a los roles autorizados
        return $this->hasAnyRole([
            'super_admin',    // Administrador del sistema
            'panel_user',     // Usuario del panel
            'Vendedor',       // Vendedor
            'Analista',       // Analista
            'Administrador',  // Administrador
            'Logistica'       // Logística
        ]);
    }


}

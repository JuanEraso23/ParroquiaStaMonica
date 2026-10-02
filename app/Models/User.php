<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos que pueden asignarse masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'documento',
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'email',
        'password',
        'rol',
        'activo',
        'cargo',
        'notas_internas',
        'last_activity',
    ];

    /**
     * Atributos que deben estar ocultos durante la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de atributos a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fecha_nacimiento' => 'date',
            'password' => 'hashed',
            'activo' => 'boolean',
            'last_activity' => 'datetime',
        ];
    }

    // ==================== VERIFICACIÓN DE ROLES ====================

    /**
     * Verifica si el usuario es un feligrés.
     */
    public function esFeligres(): bool
    {
        return $this->rol === 'feligres';
    }

    /**
     * Verifica si el usuario pertenece a secretaría.
     */
    public function esSecretaria(): bool
    {
        return $this->rol === 'secretaria';
    }

    /**
     * Verifica si el usuario es párroco.
     */
    public function esParroco(): bool
    {
        return $this->rol === 'parroco';
    }

    /**
     * Verifica si el usuario es vicario.
     */
    public function esVicario(): bool
    {
        return $this->rol === 'vicario';
    }

    /**
     * Verifica si el usuario posee un rol administrativo.
     */
    public function esAdministrador(): bool
    {
        return in_array(
            $this->rol,
            ['secretaria', 'parroco', 'vicario'],
            true
        );
    }

    // ==================== SCOPES ====================

    /**
     * Obtiene únicamente usuarios activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtiene únicamente feligreses.
     */
    public function scopeFeligreses($query)
    {
        return $query->where('rol', 'feligres');
    }

    /**
     * Obtiene únicamente usuarios administrativos.
     */
    public function scopeAdministradores($query)
    {
        return $query->whereIn(
            'rol',
            ['secretaria', 'parroco', 'vicario']
        );
    }

    // ==================== ACCESORES ====================

    /**
     * Obtiene el nombre completo del usuario.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->name} {$this->apellidos}");
    }

    /**
     * Obtiene el nombre del rol en español.
     */
    public function getRolTextoAttribute(): string
    {
        $roles = [
            'feligres' => 'Feligrés',
            'secretaria' => 'Secretaría',
            'parroco' => 'Párroco',
            'vicario' => 'Vicario',
        ];

        return $roles[$this->rol] ?? ucfirst($this->rol);
    }

    /**
     * Obtiene la clase de color correspondiente al rol.
     */
    public function getRolBadgeAttribute(): string
    {
        $colores = [
            'feligres' => 'secondary',
            'secretaria' => 'info',
            'parroco' => 'danger',
            'vicario' => 'warning',
        ];

        return $colores[$this->rol] ?? 'secondary';
    }

    // ==================== RELACIONES CON CITAS ====================

    /**
     * Citas solicitadas por el usuario como feligrés.
     */
    public function citasSolicitadas()
    {
        return $this->hasMany(Cita::class, 'feligres_id');
    }

    /**
     * Citas asignadas al usuario como sacerdote.
     */
    public function citasAsignadas()
    {
        return $this->hasMany(Cita::class, 'sacerdote_id');
    }

    // ==================== RELACIONES CON PETICIONES ====================

    /**
     * Peticiones registradas por el usuario como feligrés.
     */
    public function peticiones()
    {
        return $this->hasMany(Peticion::class, 'feligres_id');
    }

    /**
     * Peticiones asignadas al usuario como sacerdote.
     */
    public function peticionesAsignadas()
    {
        return $this->hasMany(Peticion::class, 'sacerdote_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que son asignables masivamente.
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
        'last_activity'
    ];

    /**
     * Los atributos que deben estar ocultos para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fecha_nacimiento' => 'date',
            'password' => 'hashed',
            'activo' => 'boolean',
            'last_activity' => 'datetime',  // ← Agrega esta línea
        ];
    }

    // ==================== MÉTODOS DE VERIFICACIÓN DE ROLES ====================
    
    /**
     * Verifica si el usuario es un feligrés común
     */
    public function esFeligres()
    {
        return $this->rol === 'feligres';
    }

    /**
     * Verifica si el usuario es secretaria
     */
    public function esSecretaria()
    {
        return $this->rol === 'secretaria';
    }

    /**
     * Verifica si el usuario es párroco
     */
    public function esParroco()
    {
        return $this->rol === 'parroco';
    }

    /**
     * Verifica si el usuario es vicario
     */
    public function esVicario()
    {
        return $this->rol === 'vicario';
    }

    /**
     * Verifica si el usuario es administrador (secretaria, parroco o vicario)
     */
    public function esAdministrador()
    {
        return in_array($this->rol, ['secretaria', 'parroco', 'vicario']);
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope para obtener solo usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener solo feligreses
     */
    public function scopeFeligreses($query)
    {
        return $query->where('rol', 'feligres');
    }

    /**
     * Scope para obtener solo administradores
     */
    public function scopeAdministradores($query)
    {
        return $query->whereIn('rol', ['secretaria', 'parroco', 'vicario']);
    }

    // ==================== ACCESORES (ATRIBUTOS DINÁMICOS) ====================
    
    /**
     * Obtiene el nombre completo del usuario
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->name} {$this->apellidos}";
    }

    /**
     * Obtiene el rol en español con primera letra mayúscula
     */
    public function getRolTextoAttribute()
    {
        $roles = [
            'feligres' => 'Feligrés',
            'secretaria' => 'Secretaria',
            'parroco' => 'Párroco',
            'vicario' => 'Vicario',
        ];
        
        return $roles[$this->rol] ?? ucfirst($this->rol);
    }

    /**
     * Obtiene el badge de color según el rol
     */
    public function getRolBadgeAttribute()
    {
        $colores = [
            'feligres' => 'secondary',
            'secretaria' => 'info',
            'parroco' => 'danger',
            'vicario' => 'warning',
        ];
        
        return $colores[$this->rol] ?? 'secondary';
    }

    // ==================== RELACIONES (para el futuro) ====================
    
    /**
     * Relación con las solicitudes de citas que ha hecho el feligrés
     */
    public function solicitudesCitas()
    {
        return $this->hasMany(SolicitudCita::class, 'feligres_id');
    }

    /**
     * Relación con las citas que ha atendido como administrador
     */
    public function citasAtendidas()
    {
        return $this->hasMany(SolicitudCita::class, 'administrador_id');
    }

    /**
     * Relación con las peticiones e intenciones
     */
    public function peticionesIntenciones()
    {
        return $this->hasMany(PeticionIntencion::class);
    }

    /**
     * Relación con los pagos que ha verificado
     */
    public function pagosVerificados()
    {
        return $this->hasMany(Pago::class, 'verificado_por');
    }
}
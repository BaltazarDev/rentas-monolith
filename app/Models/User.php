<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
    ];

    /**
     * Full catalog of available permissions grouped by functional area.
     */
    public const PERMISSIONS_MAP = [
        'dashboard' => [
            'label' => '📊 Dashboard y Métricas',
            'permissions' => [
                'dashboard.view' => [
                    'label' => 'Ver Dashboard',
                    'description' => 'Acceso al panel principal, tarjetas de métricas del mes y transacciones recientes.',
                ],
                'dashboard.charts' => [
                    'label' => 'Ver Gráficas Financieras',
                    'description' => 'Gráficas de ingresos vs gastos diarios y distribución de ingresos por propiedad.',
                ],
            ],
        ],
        'houses' => [
            'label' => '🏢 Propiedades',
            'permissions' => [
                'houses.view' => [
                    'label' => 'Consultar Propiedades',
                    'description' => 'Ver catálogo de propiedades y su vista de detalle.',
                ],
                'houses.create' => [
                    'label' => 'Crear Propiedades',
                    'description' => 'Dar de alta nuevas propiedades y edificios.',
                ],
                'houses.edit' => [
                    'label' => 'Editar Propiedades',
                    'description' => 'Modificar datos, fotos y mapa de propiedades.',
                ],
                'houses.delete' => [
                    'label' => 'Archivar / Eliminar Propiedades',
                    'description' => 'Archivar, desarchivar o borrar propiedades.',
                ],
            ],
        ],
        'units' => [
            'label' => '🚪 Unidades y Departamentos',
            'permissions' => [
                'units.create' => [
                    'label' => 'Crear Unidades',
                    'description' => 'Agregar departamentos o locales a una propiedad.',
                ],
                'units.edit' => [
                    'label' => 'Editar Unidades',
                    'description' => 'Modificar costo de renta base, nombre y tipo de unidad.',
                ],
                'units.delete' => [
                    'label' => 'Eliminar Unidades',
                    'description' => 'Eliminar departamentos desocupados.',
                ],
            ],
        ],
        'tenants' => [
            'label' => '👥 Inquilinos',
            'permissions' => [
                'tenants.view' => [
                    'label' => 'Ver Inquilinos Activos',
                    'description' => 'Ver directorio de inquilinos con contrato vigente.',
                ],
                'tenants.view_all' => [
                    'label' => 'Ver Inquilinos Históricos / Inactivos',
                    'description' => 'Ver contratos terminados e inquilinos dados de baja.',
                ],
                'tenants.create' => [
                    'label' => 'Crear Inquilinos',
                    'description' => 'Registrar nuevos inquilinos y contratos.',
                ],
                'tenants.edit' => [
                    'label' => 'Editar Inquilinos',
                    'description' => 'Modificar datos, teléfonos, montos, fechas de corte y notas.',
                ],
                'tenants.delete' => [
                    'label' => 'Eliminar Inquilinos',
                    'description' => 'Eliminar registros de inquilinos del sistema.',
                ],
            ],
        ],
        'finance' => [
            'label' => '💰 Finanzas y Transacciones',
            'permissions' => [
                'payments.create' => [
                    'label' => 'Cobrar Renta (Ingresos)',
                    'description' => 'Registrar pagos y cobros de inquilinos.',
                ],
                'expenses.create' => [
                    'label' => 'Registrar Gastos',
                    'description' => 'Registrar gastos y egresos de propiedades.',
                ],
                'transactions.view' => [
                    'label' => 'Historial Financiero General',
                    'description' => 'Consultar el historial financiero completo con filtros avanzados.',
                ],
                'payments.edit' => [
                    'label' => 'Editar Transacciones',
                    'description' => 'Modificar o corregir cobros y comprobantes guardados.',
                ],
            ],
        ],
        'system' => [
            'label' => '⚙️ Sistema y Datos',
            'permissions' => [
                'import.data' => [
                    'label' => 'Carga Masiva (Excel)',
                    'description' => 'Importar propiedades, unidades e inquilinos desde archivo Excel.',
                ],
                'database.backup' => [
                    'label' => 'Exportar Base de Datos',
                    'description' => 'Descargar respaldo completo de la base de datos.',
                ],
                'users.manage' => [
                    'label' => 'Administrar Usuarios y Accesos',
                    'description' => 'Crear y editar cuentas de usuario y sus permisos.',
                ],
            ],
        ],
    ];

    /**
     * Default permissions assigned to each standard role.
     */
    public const ROLE_DEFAULT_PERMISSIONS = [
        'operator' => [
            'houses.view',
            'tenants.view',
            'payments.create',
            'expenses.create',
        ],
        'admin' => [
            'dashboard.view',
            'dashboard.charts',
            'houses.view',
            'houses.create',
            'houses.edit',
            'houses.delete',
            'units.create',
            'units.edit',
            'units.delete',
            'tenants.view',
            'tenants.view_all',
            'tenants.create',
            'tenants.edit',
            'tenants.delete',
            'payments.create',
            'expenses.create',
            'transactions.view',
            'payments.edit',
        ],
        'super_admin' => [],
    ];

    /**
     * Get flat list of all permission identifiers.
     */
    public static function getAllPermissionKeys(): array
    {
        $keys = [];
        foreach (self::PERMISSIONS_MAP as $group) {
            foreach (array_keys($group['permissions']) as $k) {
                $keys[] = $k;
            }
        }
        return $keys;
    }

    /**
     * Get effective permissions for this user (custom permissions or role defaults).
     */
    public function getEffectivePermissions(): array
    {
        if ($this->isSuperAdmin()) {
            return self::getAllPermissionKeys();
        }

        if (is_array($this->permissions)) {
            return $this->permissions;
        }

        return self::ROLE_DEFAULT_PERMISSIONS[$this->role ?? 'operator'] ?? [];
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->getEffectivePermissions(), true);
    }

    /**
     * Determine if the user has custom permissions saved.
     */
    public function hasCustomPermissions(): bool
    {
        return is_array($this->permissions);
    }

    /**
     * Determine if the user is a super administrator.
     */
    public function isSuperAdmin(): bool
    {
        return ($this->role ?? null) === 'super_admin'
            || in_array(strtolower($this->email ?? ''), ['sistemascreativos@hotmail.com', 'bgdevsoft@gmail.com']);
    }

    /**
     * Determine if the user is an administrator or super administrator.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role ?? null, ['admin', 'super_admin'])
            || $this->isSuperAdmin();
    }

    /**
     * Determine if the user is an operator.
     */
    public function isOperator(): bool
    {
        return ($this->role ?? null) === 'operator' && !$this->isSuperAdmin();
    }

    /**
     * Get the access logs for the user.
     */
    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }
}

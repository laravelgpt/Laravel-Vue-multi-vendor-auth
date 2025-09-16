<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'country_code',
        'address',
        'country',
        'state',
        'city',
        'zip_code',
        'role',
        'avatar',
        'is_active',
        'is_admin',
        'bio',
        'location',
        'website',
        'twitter',
        'linkedin',
        'github',
        'last_login_at',
    ];

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
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    /**
     * Check if user is wholeseller
     */
    public function isWholeseller(): bool
    {
        return $this->role === 'wholeseller';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user is technician
     */
    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return in_array($this->role, $roles);
    }

    /**
     * Check if user has any of the specified permissions
     */
    public function hasPermission(string|array $permissions): bool
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Define role-based permissions
        $rolePermissions = [
            'wholeseller' => [
                'view_products',
                'manage_products',
                'view_orders',
                'manage_orders',
                'view_customers',
                'manage_inventory',
                'view_reports',
            ],
            'customer' => [
                'view_products',
                'place_orders',
                'view_own_orders',
                'manage_profile',
            ],
            'technician' => [
                'view_repairs',
                'manage_repairs',
                'view_repair_orders',
                'update_repair_status',
                'view_repair_history',
                'manage_repair_parts',
                'view_repair_reports',
            ],
        ];

        $userPermissions = $rolePermissions[$this->role] ?? [];

        return ! empty(array_intersect($permissions, $userPermissions));
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get user's display name
     */
    public function getDisplayName(): string
    {
        return $this->name ?: explode('@', $this->email)[0];
    }

    /**
     * Get user's avatar URL
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->getDisplayName()).'&color=7C3AED&background=EBF4FF';
    }

    /**
     * Get the vendor profile associated with the user
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Check if user is a vendor
     */
    public function isVendor(): bool
    {
        return $this->vendor()->exists();
    }
}

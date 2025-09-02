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
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'is_active',
        'bio',
        'location',
        'website',
        'twitter',
        'linkedin',
        'github',
        'date_of_birth',
        'gender',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'alternate_email',
        'emergency_contact',
        'emergency_phone',
        'company',
        'job_title',
        'department',
        'employee_id',
        'timezone',
        'language',
        'notification_preferences',
        'facebook',
        'instagram',
        'youtube',
        'tiktok',
        'interests',
        'skills',
        'education',
        'experience',
        'email_verified',
        'phone_verified',
        'last_login_at',
        'registration_ip',
        'last_login_ip',
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
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'email_verified' => 'boolean',
            'phone_verified' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
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
     * Get full name
     */
    public function getFullName(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name.' '.$this->last_name;
        }

        return $this->name;
    }

    /**
     * Get full address
     */
    public function getFullAddress(): string
    {
        $address = [];
        if ($this->address_line_1) {
            $address[] = $this->address_line_1;
        }
        if ($this->address_line_2) {
            $address[] = $this->address_line_2;
        }
        if ($this->city) {
            $address[] = $this->city;
        }
        if ($this->state) {
            $address[] = $this->state;
        }
        if ($this->postal_code) {
            $address[] = $this->postal_code;
        }
        if ($this->country) {
            $address[] = $this->country;
        }

        return implode(', ', $address);
    }

    /**
     * Check if user has complete profile
     */
    public function hasCompleteProfile(): bool
    {
        return ! empty($this->first_name) &&
               ! empty($this->last_name) &&
               ! empty($this->phone) &&
               ! empty($this->address_line_1) &&
               ! empty($this->city) &&
               ! empty($this->country);
    }

    /**
     * Get profile completion percentage
     */
    public function getProfileCompletionPercentage(): int
    {
        $fields = [
            'first_name', 'last_name', 'email', 'phone', 'date_of_birth',
            'address_line_1', 'city', 'country', 'company', 'job_title',
            'bio', 'interests', 'skills',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (! empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(?string $ip = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'country_code',
        'address',
        'country',
        'state',
        'city',
        'zip_code',
        'business_type',
        'description',
        'website',
        'logo',
        'is_active',
        'is_verified',
        'rating',
        'total_orders',
        'total_revenue',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'rating' => 'decimal:2',
        'total_orders' => 'integer',
        'total_revenue' => 'decimal:2',
    ];

    /**
     * Get the user that owns the vendor profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if vendor is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if vendor is verified
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * Get vendor's display name
     */
    public function getDisplayName(): string
    {
        return $this->name ?: $this->user->name;
    }

    /**
     * Get vendor's logo URL
     */
    public function getLogoUrl(): string
    {
        if ($this->logo) {
            return $this->logo;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->getDisplayName()).'&color=7C3AED&background=EBF4FF';
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddress(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhone(): string
    {
        if (! $this->phone) {
            return '';
        }

        return $this->country_code.' '.$this->phone;
    }
}

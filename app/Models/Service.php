<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'price_per_kg',
        'price_per_item',
        'estimated_hours',
        'discount',
        'is_active',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'price_per_kg' => 'decimal:2',
        'price_per_item' => 'decimal:2',
        'discount' => 'decimal:2',
        'estimated_hours' => 'integer',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
        'discount' => 0,
        'estimated_hours' => 24,
    ];

    // Type constants
    const TYPE_KILOAN = 'kiloan';
    const TYPE_SATUAN = 'satuan';
    const TYPE_EKSPRES = 'ekspres';

    // =================== RELATIONSHIPS ===================

    /**
     * Get all transactions for this service
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // =================== SCOPES ===================

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include services by type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include kilo services.
     */
    public function scopeKiloan(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_KILOAN);
    }

    /**
     * Scope a query to only include item services.
     */
    public function scopeSatuan(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_SATUAN);
    }

    /**
     * Scope a query to only include express services.
     */
    public function scopeEkspres(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_EKSPRES);
    }

    /**
     * Scope a query to only include services with discount.
     */
    public function scopeHasDiscount(Builder $query): Builder
    {
        return $query->where('discount', '>', 0);
    }

    // =================== ACCESSORS ===================

    /**
     * Get formatted price based on service type
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->type === self::TYPE_KILOAN) {
            return 'Rp ' . number_format($this->price_per_kg, 0, ',', '.') . '/kg';
        } else {
            return 'Rp ' . number_format($this->price_per_item, 0, ',', '.') . '/item';
        }
    }

    /**
     * Get base price based on service type
     */
    public function getBasePriceAttribute(): float
    {
        return $this->type === self::TYPE_KILOAN ? $this->price_per_kg : $this->price_per_item;
    }

    /**
     * Get price after discount
     */
    public function getFinalPriceAttribute(): float
    {
        $basePrice = $this->base_price;
        return $basePrice * (1 - ($this->discount / 100));
    }

    /**
     * Get estimated completion time in human readable format
     */
    public function getEstimatedCompletionTimeAttribute(): string
    {
        $hours = $this->estimated_hours;
        
        if ($hours >= 24) {
            $days = floor($hours / 24);
            $remainingHours = $hours % 24;
            
            $result = $days . ' hari';
            if ($remainingHours > 0) {
                $result .= ' ' . $remainingHours . ' jam';
            }
            return $result;
        }
        
        return $hours . ' jam';
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_KILOAN => 'Kiloan',
            self::TYPE_SATUAN => 'Satuan',
            self::TYPE_EKSPRES => 'Ekspres',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get short description
     */
    public function getShortDescriptionAttribute(): string
    {
        return Str::limit($this->description, 100);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get badge color based on type
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_KILOAN => 'primary',
            self::TYPE_SATUAN => 'success',
            self::TYPE_EKSPRES => 'warning',
            default => 'secondary',
        };
    }

    // =================== METHODS ===================

    /**
     * Calculate price based on weight or quantity
     */
    public function calculatePrice(?float $weight = null, ?int $quantity = null): float
    {
        if ($this->type === self::TYPE_KILOAN && $weight) {
            $basePrice = $this->price_per_kg * $weight;
        } elseif (($this->type === self::TYPE_SATUAN || $this->type === self::TYPE_EKSPRES) && $quantity) {
            $basePrice = $this->price_per_item * $quantity;
        } else {
            throw new \InvalidArgumentException('Invalid parameters for service type');
        }

        $discountAmount = $basePrice * ($this->discount / 100);
        return $basePrice - $discountAmount;
    }

    /**
     * Get discount amount for given base price
     */
    public function getDiscountAmount(float $basePrice): float
    {
        return $basePrice * ($this->discount / 100);
    }

    /**
     * Check if service can be deleted
     */
    public function canDelete(): bool
    {
        return $this->transactions()->count() === 0;
    }

    /**
     * Toggle active status
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    /**
     * Get service statistics
     */
    public function getServiceStats(): array
    {
        $transactions = $this->transactions();
        
        return [
            'total_transactions' => $transactions->count(),
            'total_income' => $transactions->sum('total_amount'),
            'average_income' => $transactions->avg('total_amount'),
            'pending_transactions' => $transactions->where('status', 'diterima')->count(),
            'active_transactions' => $transactions->whereIn('status', ['diterima', 'dalam_proses'])->count(),
        ];
    }
}
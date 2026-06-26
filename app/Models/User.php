<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'password',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'role' => 'pelanggan',
        'is_active' => true,
    ];

    // =================== RELATIONSHIPS ===================

    /**
     * Get all transactions where user is customer
     */
    public function customerTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    /**
     * Get all transactions where user is cashier
     */
    public function cashierTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'cashier_id');
    }

    // =================== SCOPES ===================

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope a query to only include cashier users.
     */
    public function scopeKasir($query)
    {
        return $query->where('role', 'kasir');
    }

    /**
     * Scope a query to only include customer users.
     */
    public function scopePelanggan($query)
    {
        return $query->where('role', 'pelanggan');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include users created today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope a query to only include users created this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to only include users created this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    // =================== ACCESSORS ===================

    /**
     * Get user's role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Admin',
            'kasir' => 'Kasir',
            'pelanggan' => 'Pelanggan',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get user's status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get user's formatted phone number
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->phone) return null;
        
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (strlen($phone) > 10) {
            return '+62 ' . substr($phone, -10, 3) . '-' . substr($phone, -7, 4) . '-' . substr($phone, -3);
        }
        
        return $this->phone;
    }

    /**
     * Get user's initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $name = explode(' ', $this->name);
        $initials = '';
        
        if (count($name) >= 2) {
            $initials = strtoupper(substr($name[0], 0, 1) . substr($name[1], 0, 1));
        } else {
            $initials = strtoupper(substr($this->name, 0, 2));
        }
        
        return $initials;
    }

    /**
     * Get user's badge color based on role
     */
    public function getRoleBadgeColorAttribute(): string
    {
        return match($this->role) {
            'admin' => 'danger',
            'kasir' => 'warning',
            'pelanggan' => 'success',
            default => 'secondary',
        };
    }

    // =================== METHODS ===================

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is cashier
     */
    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    /**
     * Check if user is customer
     */
    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    /**
     * Check if user can be deleted
     */
    public function canDelete(): bool
    {
        // User cannot be deleted if they have transactions
        return $this->customerTransactions()->count() === 0 && 
               $this->cashierTransactions()->count() === 0;
    }

    /**
     * Get user's transaction statistics
     */
    public function getTransactionStats(): array
    {
        $transactions = $this->customerTransactions();
        
        return [
            'total' => $transactions->count(),
            'pending' => $transactions->where('status', 'diterima')->count(),
            'processing' => $transactions->where('status', 'dalam_proses')->count(),
            'completed' => $transactions->where('status', 'selesai')->count(),
            'picked_up' => $transactions->where('status', 'diambil')->count(),
            'total_spent' => $transactions->where('is_paid', true)->sum('total_amount'),
        ];
    }

    /**
     * Get user's monthly spending
     */
    public function getMonthlySpending($months = 6): array
    {
        $data = [];
        $now = now();
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthName = $month->format('M');
            
            $total = $this->customerTransactions()
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->where('is_paid', true)
                ->sum('total_amount');
            
            $data[$monthName] = $total;
        }
        
        return $data;
    }

    /**
     * Toggle user active status
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    /**
     * Reset user password
     */
    public function resetPassword(string $newPassword): bool
    {
        $this->password = bcrypt($newPassword);
        return $this->save();
    }

    /**
     * Update user profile
     */
    public function updateProfile(array $data): bool
    {
        return $this->update($data);
    }
}
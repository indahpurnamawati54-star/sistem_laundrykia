<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'cashier_id',
        'service_id',
        'weight',
        'quantity',
        'price',
        'discount',
        'total_amount',
        'status',
        'payment_method',
        'is_paid',
        'notes',
        'received_at',
        'process_started_at',
        'completed_at',
        'picked_up_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_paid' => 'boolean',
        'received_at' => 'datetime',
        'process_started_at' => 'datetime',
        'completed_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Status constants
    const STATUS_DITERIMA = 'diterima';
    const STATUS_DALAM_PROSES = 'dalam_proses';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIAMBIL = 'diambil';

    const STATUSES = [
        self::STATUS_DITERIMA => 'Diterima',
        self::STATUS_DALAM_PROSES => 'Dalam Proses',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DIAMBIL => 'Diambil',
    ];

    // Payment method constants
    const PAYMENT_CASH = 'cash';
    const PAYMENT_TRANSFER = 'transfer';
    const PAYMENT_EWALLET = 'e-wallet';

    const PAYMENT_METHODS = [
        self::PAYMENT_CASH => 'Cash',
        self::PAYMENT_TRANSFER => 'Transfer Bank',
        self::PAYMENT_EWALLET => 'E-Wallet',
    ];

    // =================== RELATIONSHIPS ===================

    /**
     * Get the customer that owns the transaction
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the cashier that owns the transaction
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the service that owns the transaction
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // =================== SCOPES ===================

    /**
     * Scope a query to only include today's transactions.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope a query to only include yesterday's transactions.
     */
    public function scopeYesterday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today()->subDay());
    }

    /**
     * Scope a query to only include this week's transactions.
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to only include this month's transactions.
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope a query to only include transactions by status.
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include paid transactions.
     */
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope a query to only include unpaid transactions.
     */
    public function scopeUnpaid(Builder $query): Builder
    {
        return $query->where('is_paid', false);
    }

    /**
     * Scope a query to only include transactions by cashier.
     */
    public function scopeByCashier(Builder $query, int $cashierId): Builder
    {
        return $query->where('cashier_id', $cashierId);
    }

    /**
     * Scope a query to only include transactions by customer.
     */
    public function scopeByCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope a query to only include transactions by service.
     */
    public function scopeByService(Builder $query, int $serviceId): Builder
    {
        return $query->where('service_id', $serviceId);
    }

    // =================== ACCESSORS ===================

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted discount
     */
    public function getFormattedDiscountAttribute(): string
    {
        return 'Rp ' . number_format($this->discount, 0, ',', '.');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get estimated completion time
     */
    public function getEstimatedCompletionTimeAttribute(): ?Carbon
    {
        if (!$this->received_at) {
            return null;
        }

        $completionHours = $this->service->estimated_hours;
        return $this->received_at->copy()->addHours($completionHours);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): int
    {
        return match($this->status) {
            self::STATUS_DITERIMA => 25,
            self::STATUS_DALAM_PROSES => 50,
            self::STATUS_SELESAI => 75,
            self::STATUS_DIAMBIL => 100,
            default => 0,
        };
    }

    /**
     * Get badge color based on status
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DITERIMA => 'warning',
            self::STATUS_DALAM_PROSES => 'info',
            self::STATUS_SELESAI => 'success',
            self::STATUS_DIAMBIL => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return $this->is_paid ? 'Lunas' : 'Belum Lunas';
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentStatusBadgeColorAttribute(): string
    {
        return $this->is_paid ? 'success' : 'danger';
    }

    // =================== METHODS ===================

    /**
     * Update transaction status
     */
    public function updateStatus(string $status): bool
    {
        if (!array_key_exists($status, self::STATUSES)) {
            throw new \InvalidArgumentException('Invalid status');
        }

        $this->status = $status;

        $timestampField = match($status) {
            self::STATUS_DITERIMA => 'received_at',
            self::STATUS_DALAM_PROSES => 'process_started_at',
            self::STATUS_SELESAI => 'completed_at',
            self::STATUS_DIAMBIL => 'picked_up_at',
            default => null,
        };

        if ($timestampField && !$this->$timestampField) {
            $this->$timestampField = now();
        }

        // Auto mark as paid when picked up
        if ($status === self::STATUS_DIAMBIL) {
            $this->is_paid = true;
        }

        return $this->save();
    }

    /**
     * Process payment
     */
    public function processPayment(string $method): bool
    {
        if (!array_key_exists($method, self::PAYMENT_METHODS)) {
            throw new \InvalidArgumentException('Invalid payment method');
        }

        $this->payment_method = $method;
        $this->is_paid = true;

        return $this->save();
    }

    /**
     * Check if status can be updated
     */
    public function canUpdateStatus(string $newStatus): bool
    {
        $currentStatus = $this->status;

        $allowedTransitions = [
            self::STATUS_DITERIMA => [self::STATUS_DALAM_PROSES],
            self::STATUS_DALAM_PROSES => [self::STATUS_SELESAI],
            self::STATUS_SELESAI => [self::STATUS_DIAMBIL],
            self::STATUS_DIAMBIL => [],
        ];

        return in_array($newStatus, $allowedTransitions[$currentStatus] ?? []);
    }

    /**
     * Get transaction timeline
     */
    public function getTimeline(): array
    {
        return [
            [
                'status' => 'Diterima',
                'icon' => 'inventory_2',
                'time' => $this->received_at,
                'completed' => (bool) $this->received_at,
                'description' => 'Cucian diterima oleh kasir',
            ],
            [
                'status' => 'Dalam Proses',
                'icon' => 'local_laundry_service',
                'time' => $this->process_started_at,
                'completed' => (bool) $this->process_started_at,
                'description' => 'Sedang dalam proses pencucian',
            ],
            [
                'status' => 'Selesai',
                'icon' => 'check_circle',
                'time' => $this->completed_at,
                'completed' => (bool) $this->completed_at,
                'description' => 'Cucian sudah selesai',
            ],
            [
                'status' => 'Diambil',
                'icon' => 'local_shipping',
                'time' => $this->picked_up_at,
                'completed' => (bool) $this->picked_up_at,
                'description' => 'Cucian sudah diambil pelanggan',
            ],
        ];
    }

    /**
     * Generate invoice number
     */
 /**
 * Generate invoice number
 */
public static function generateInvoiceNumber(): string
{
    $date = now()->format('Ymd');

    // Ambil invoice terakhir berdasarkan nomor invoice hari ini
    $lastInvoice = self::where('invoice_number', 'like', 'INV-' . $date . '-%')
        ->orderBy('invoice_number', 'desc')
        ->first();

    if ($lastInvoice) {
        // Ambil 4 angka terakhir dari invoice
        $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '0001';
    }

    return 'INV-' . $date . '-' . $newNumber;
}
    /**
     * Get transaction summary
     */
    public function getSummary(): array
    {
        return [
            'invoice_number' => $this->invoice_number,
            'customer_name' => $this->customer->name,
            'service_name' => $this->service->name,
            'total_amount' => $this->formatted_total_amount,
            'status' => $this->status_label,
            'payment_status' => $this->payment_status_label,
            'created_at' => $this->created_at->format('d F Y H:i'),
            'estimated_completion' => $this->estimated_completion_time?->format('d F Y H:i'),
        ];
    }

    /**
     * Check if transaction can be deleted
     */
    public function canDelete(): bool
    {
        // Only can delete recent transactions that haven't been processed
        return $this->status === self::STATUS_DITERIMA &&
               $this->created_at->diffInHours(now()) <= 24;
    }
}

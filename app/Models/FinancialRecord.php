<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\FinancialRecord
 *
 * @property int $id
 * @property string $record_number
 * @property string $type
 * @property string $category
 * @property string $description
 * @property float $amount
 * @property int|null $household_id
 * @property int $recorded_by
 * @property \Illuminate\Support\Carbon $transaction_date
 * @property string|null $payment_method
 * @property string|null $payment_reference
 * @property string $status
 * @property string|null $notes
 * @property array|null $attachments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Household|null $household
 * @property-read \App\Models\User $recordedBy
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereRecordNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereHouseholdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereRecordedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord income()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialRecord expense()
 * @method static \Database\Factories\FinancialRecordFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class FinancialRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'record_number',
        'type',
        'category',
        'description',
        'amount',
        'household_id',
        'recorded_by',
        'transaction_date',
        'payment_method',
        'payment_reference',
        'status',
        'notes',
        'attachments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the household associated with this record.
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get the user who recorded this transaction.
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Scope a query to only include income records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['income', 'contribution', 'donation']);
    }

    /**
     * Scope a query to only include expense records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Generate a unique record number.
     *
     * @return string
     */
    public static function generateRecordNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "KU/{$month}/{$year}";
        
        $lastNumber = self::where('record_number', 'like', "{$prefix}/%")
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($lastNumber) {
            $parts = explode('/', $lastNumber->record_number);
            $number = (int)end($parts) + 1;
        } else {
            $number = 1;
        }
        
        return "{$prefix}/" . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Format amount for display.
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
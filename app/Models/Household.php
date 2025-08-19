<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Household
 *
 * @property int $id
 * @property string $house_number
 * @property string $rt_number
 * @property string $rw_number
 * @property string $head_name
 * @property string|null $phone
 * @property string|null $email
 * @property string $address
 * @property string $status
 * @property int $resident_count
 * @property float $monthly_contribution
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resident> $residents
 * @property-read int|null $residents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdministrativeLetter> $administrativeLetters
 * @property-read int|null $administrative_letters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinancialRecord> $financialRecords
 * @property-read int|null $financial_records_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Household newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Household newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Household query()
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereHouseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereRtNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereRwNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereHeadName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereResidentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereMonthlyContribution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household active()
 * @method static \Database\Factories\HouseholdFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Household extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'house_number',
        'rt_number',
        'rw_number',
        'head_name',
        'phone',
        'email',
        'address',
        'status',
        'resident_count',
        'monthly_contribution',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monthly_contribution' => 'decimal:2',
        'resident_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the residents for the household.
     */
    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Get the administrative letters for the household.
     */
    public function administrativeLetters(): HasMany
    {
        return $this->hasMany(AdministrativeLetter::class);
    }

    /**
     * Get the financial records for the household.
     */
    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class);
    }

    /**
     * Scope a query to only include active households.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the full RT/RW identifier.
     *
     * @return string
     */
    public function getRtRwAttribute(): string
    {
        return "RT {$this->rt_number} / RW {$this->rw_number}";
    }

    /**
     * Get the household identifier (house number + RT/RW).
     *
     * @return string
     */
    public function getIdentifierAttribute(): string
    {
        return "{$this->house_number} - {$this->getRtRwAttribute()}";
    }
}
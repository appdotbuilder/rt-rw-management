<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Resident
 *
 * @property int $id
 * @property int $household_id
 * @property string $name
 * @property string $id_number
 * @property \Illuminate\Support\Carbon $birth_date
 * @property string $gender
 * @property string $relationship
 * @property string|null $occupation
 * @property string|null $phone
 * @property string|null $email
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $moved_in_date
 * @property \Illuminate\Support\Carbon|null $moved_out_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Household $household
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdministrativeLetter> $requestedLetters
 * @property-read int|null $requested_letters_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereHouseholdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereMovedInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereMovedOutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident active()
 * @method static \Database\Factories\ResidentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Resident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'name',
        'id_number',
        'birth_date',
        'gender',
        'relationship',
        'occupation',
        'phone',
        'email',
        'status',
        'moved_in_date',
        'moved_out_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'moved_in_date' => 'date',
        'moved_out_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the household that owns the resident.
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get the administrative letters requested by this resident.
     */
    public function requestedLetters(): HasMany
    {
        return $this->hasMany(AdministrativeLetter::class, 'requester_id');
    }

    /**
     * Scope a query to only include active residents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the resident's age.
     *
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    /**
     * Get the resident's full identifier.
     *
     * @return string
     */
    public function getFullIdentifierAttribute(): string
    {
        return "{$this->name} ({$this->id_number})";
    }
}
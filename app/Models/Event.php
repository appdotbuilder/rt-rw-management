<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $type
 * @property \Illuminate\Support\Carbon $start_datetime
 * @property \Illuminate\Support\Carbon $end_datetime
 * @property string $location
 * @property int $organizer_id
 * @property int|null $max_participants
 * @property float $participation_fee
 * @property string $status
 * @property string|null $agenda
 * @property array|null $required_items
 * @property array|null $target_participants
 * @property bool $requires_registration
 * @property \Illuminate\Support\Carbon|null $registration_deadline
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $organizer
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereMaxParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereParticipationFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAgenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRequiredItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTargetParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRequiresRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRegistrationDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|Event active()
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'start_datetime',
        'end_datetime',
        'location',
        'organizer_id',
        'max_participants',
        'participation_fee',
        'status',
        'agenda',
        'required_items',
        'target_participants',
        'requires_registration',
        'registration_deadline',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'participation_fee' => 'decimal:2',
        'required_items' => 'array',
        'target_participants' => 'array',
        'requires_registration' => 'boolean',
        'registration_deadline' => 'datetime',
        'max_participants' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who organized this event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Scope a query to only include upcoming events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now())
            ->orderBy('start_datetime');
    }

    /**
     * Scope a query to only include active events (confirmed or ongoing).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'ongoing']);
    }

    /**
     * Check if the event is happening today.
     *
     * @return bool
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->start_datetime->isToday();
    }

    /**
     * Check if the event is in the past.
     *
     * @return bool
     */
    public function getIsPastAttribute(): bool
    {
        return $this->end_datetime->isPast();
    }

    /**
     * Get formatted date range.
     *
     * @return string
     */
    public function getDateRangeAttribute(): string
    {
        if ($this->start_datetime->isSameDay($this->end_datetime)) {
            return $this->start_datetime->format('d M Y, H:i') . 
                   ' - ' . $this->end_datetime->format('H:i');
        }
        
        return $this->start_datetime->format('d M Y, H:i') . 
               ' - ' . $this->end_datetime->format('d M Y, H:i');
    }

    /**
     * Get the event status badge color.
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'green',
            'ongoing' => 'blue',
            'completed' => 'gray',
            'cancelled' => 'red',
            'planned' => 'yellow',
            default => 'gray',
        };
    }
}
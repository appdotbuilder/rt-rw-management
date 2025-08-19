<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $rt_number
 * @property string|null $rw_number
 * @property bool $is_active
 * @property string|null $profile_notes
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdministrativeLetter> $approvedLetters
 * @property-read int|null $approved_letters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinancialRecord> $recordedTransactions
 * @property-read int|null $recorded_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement> $announcements
 * @property-read int|null $announcements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $organizedEvents
 * @property-read int|null $organized_events_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRtNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRwNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Illuminate\Database\Eloquent\Builder|User byRole($role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'rt_number',
        'rw_number',
        'is_active',
        'profile_notes',
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the administrative letters approved by this user.
     */
    public function approvedLetters(): HasMany
    {
        return $this->hasMany(AdministrativeLetter::class, 'approved_by');
    }

    /**
     * Get the financial records recorded by this user.
     */
    public function recordedTransactions(): HasMany
    {
        return $this->hasMany(FinancialRecord::class, 'recorded_by');
    }

    /**
     * Get the announcements created by this user.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    /**
     * Get the events organized by this user.
     */
    public function organizedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get the user's role display name.
     *
     * @return string
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'system_admin' => 'System Administrator',
            'rt_head' => 'RT Head',
            'rw_head' => 'RW Head',
            'management' => 'Management',
            'resident' => 'Resident',
            default => 'Unknown',
        };
    }

    /**
     * Check if user has administrative privileges.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['system_admin', 'rt_head', 'rw_head', 'management']);
    }

    /**
     * Get the RT/RW identifier for this user.
     *
     * @return string|null
     */
    public function getRtRwAttribute(): string|null
    {
        if ($this->rt_number && $this->rw_number) {
            return "RT {$this->rt_number} / RW {$this->rw_number}";
        }
        
        return null;
    }
}

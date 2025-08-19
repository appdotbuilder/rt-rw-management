<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Announcement
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $type
 * @property string $priority
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $publish_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property string $status
 * @property bool $send_notification
 * @property array|null $target_audience
 * @property array|null $attachments
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereSendNotification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTargetAudience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 * @method static \Database\Factories\AnnouncementFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'created_by',
        'publish_at',
        'expires_at',
        'status',
        'send_notification',
        'target_audience',
        'attachments',
        'view_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publish_at' => 'datetime',
        'expires_at' => 'datetime',
        'send_notification' => 'boolean',
        'target_audience' => 'array',
        'attachments' => 'array',
        'view_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include published announcements.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            });
    }

    /**
     * Scope a query to only include active announcements (published and not expired).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $this->scopePublished($query)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Increment the view count.
     *
     * @return void
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Check if the announcement is expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get the priority badge color.
     *
     * @return string
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'medium' => 'blue',
            'low' => 'gray',
            default => 'gray',
        };
    }
}
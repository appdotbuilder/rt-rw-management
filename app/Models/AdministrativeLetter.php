<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AdministrativeLetter
 *
 * @property int $id
 * @property string $letter_number
 * @property string $type
 * @property string $subject
 * @property string $content
 * @property int|null $requester_id
 * @property int|null $household_id
 * @property int|null $approved_by
 * @property string $status
 * @property \Illuminate\Support\Carbon $request_date
 * @property \Illuminate\Support\Carbon|null $approved_date
 * @property \Illuminate\Support\Carbon|null $completed_date
 * @property string|null $approval_notes
 * @property string|null $rejection_reason
 * @property array|null $required_documents
 * @property array|null $attachments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Resident|null $requester
 * @property-read \App\Models\Household|null $household
 * @property-read \App\Models\User|null $approver
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereLetterNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereRequesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereHouseholdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereCompletedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereApprovalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereRequiredDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdministrativeLetter pending()
 * @method static \Database\Factories\AdministrativeLetterFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class AdministrativeLetter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'letter_number',
        'type',
        'subject',
        'content',
        'requester_id',
        'household_id',
        'approved_by',
        'status',
        'request_date',
        'approved_date',
        'completed_date',
        'approval_notes',
        'rejection_reason',
        'required_documents',
        'attachments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request_date' => 'date',
        'approved_date' => 'date',
        'completed_date' => 'date',
        'required_documents' => 'array',
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the resident who requested this letter.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'requester_id');
    }

    /**
     * Get the household associated with this letter.
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get the user who approved this letter.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include pending letters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate a unique letter number.
     *
     * @return string
     */
    public static function generateLetterNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "SURAT/{$month}/{$year}";
        
        $lastNumber = self::where('letter_number', 'like', "{$prefix}/%")
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($lastNumber) {
            $parts = explode('/', $lastNumber->letter_number);
            $number = (int)end($parts) + 1;
        } else {
            $number = 1;
        }
        
        return "{$prefix}/" . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    public const YEAR_OF_STUDY = 'Year 1';

    protected $fillable = [
        'event_id',
        'full_name',
        'registration_number',
        'registration_number_normalized',
        'phone',
        'phone_normalized',
        'email',
        'year_of_study',
        'status',
        'registration_id',
        'verification_token',
        'accepted_at',
        'email_sent_at',
        'email_send_count',
    ];

    protected $hidden = [
        'verification_token',
    ];

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'email_sent_at' => 'datetime',
            'email_send_count' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            default => 'Pending',
        };
    }
}

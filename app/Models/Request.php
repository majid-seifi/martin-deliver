<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $intermediary_id
 * @property int $sender_id
 * @property int $recipient_id
 * @property int $delivery_id
 * @property string $status
 */
class Request extends Model
{
    use HasFactory;
    const STATUS_REGISTERED = 'registered';
    const STATUS_CANCELED = 'canceled';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    protected $fillable = [
        'intermediary_id',
        'sender_id',
        'recipient_id',
        'delivery_id',
        'status',
    ];

    public function intermediary(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function sender(): BelongsTo {
        return $this->belongsTo(Customer::class)
            ->selectRaw(Customer::SELECT_RAW);
    }
    public function recipient(): BelongsTo {
        return $this->belongsTo(Customer::class)
            ->selectRaw(Customer::SELECT_RAW);
    }
    public function delivery(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function webhook(): string {
        return $this->belongsTo(Intermediary::class, 'intermediary_id', 'intermediary_id')->first()->webhook;
    }
}

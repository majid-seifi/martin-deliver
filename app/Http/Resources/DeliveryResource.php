<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $intermediary
 * @property Customer $sender
 *  @property Customer $recipient
 * @property mixed $created_at
 */
class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'intermediary' => $this->intermediary->name,
            'sender' => $this->sender,
            'recipient' => $this->recipient,
            'created_at' => $this->created_at,
        ];
    }
}

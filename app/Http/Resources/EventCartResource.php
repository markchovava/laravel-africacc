<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'joining_fee' => $this->joining_fee,
            'number_of_people' => $this->number_of_people,
            'event_total' => $this->event_total,
            'cart_token' => $this->cart_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'event' => new EventResource($this->whenLoaded('event')),
        ];
    }
}

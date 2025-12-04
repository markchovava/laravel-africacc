<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventOrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'event_id' => $this->event_id,
            'joining_fee' => $this->joining_fee,
            'event_total' => $this->event_total,
            'is_agree' => $this->is_agree,
            'name' => $this->name,
            'address' => $this->address,
            'company_name' => $this->company_name,
            'country' => $this->country,
            'email' => $this->email,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'phone' => $this->phone,
            'profession' => $this->profession,
            'number_of_people' => $this->number_of_people,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'event' => new EventResource($this->whenLoaded('event')),
        ];
    }
}

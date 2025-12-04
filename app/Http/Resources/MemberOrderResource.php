<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberOrderResource extends JsonResource
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
            'member_id' => $this->member_id,
            'user_id' => $this->user_id,
            'member_fee' => $this->member_fee,
            'paid_amount' => $this->paid_amount,
            'start_date' => $this->start_date,
            'duration' => $this->duration,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'member' => new MemberResource($this->whenLoaded('member')),
            'member_order_info' => new MemberOrderInfoResource($this->whenLoaded('member_order_info')),
            'membership' => new MembershipResource($this->whenLoaded('membership')),
        ];
    }
}

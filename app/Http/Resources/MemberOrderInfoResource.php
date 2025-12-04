<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberOrderInfoResource extends JsonResource
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
            'member_order_id' => $this->member_order_id,
            'membership_id' => $this->membership_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'website' => $this->website,
            'who_join' => $this->who_join,
            'country' => $this->country,
            'address' => $this->address,
            'email' => $this->email,
            'profession' => $this->profession,
            'company_name' => $this->company_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'member_order' => new MemberOrderResource($this->whenLoaded('member_order')),
        ];
    }
}

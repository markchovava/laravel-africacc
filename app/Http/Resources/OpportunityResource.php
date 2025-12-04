<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityResource extends JsonResource
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
            'amount' => $this->amount,
            'country_id' => $this->country_id,
            'description' => $this->description,
            'expected_return' => $this->expected_return,
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status,
            'priority' => $this->priority,
            'short_description' => $this->short_description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'country' => new CountryResource($this->whenLoaded('country')),
            'investment' => new InvestmentResource($this->whenLoaded('investment')),
            'user' => new UserResource($this->whenLoaded('user')),
            'opportunity_images' => OpportunityImageResource::collection($this->whenLoaded('opportunity_images')),
            'sectors' => SectorResource::collection($this->whenLoaded('sectors')),
        ];
    }
}

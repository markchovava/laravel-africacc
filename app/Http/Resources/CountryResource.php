<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'invest_link' => $this->invest_link,
            'priority' => $this->priority,
            'description' => $this->description,
            'portrait' => $this->portrait,
            'landscape' => $this->landscape,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'opportunities' => OpportunityResource::collection($this->whenLoaded('opportunities')),
        ];
    }
}

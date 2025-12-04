<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpportunitySectorResource extends JsonResource
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
            'opportunity_id' => $this->opportunity_id,
            'sector_id' => $this->sector_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'opportunity' => new OpportunityResource($this->whenLoaded('opportunity')),
            'sector' => new SectorResource($this->whenLoaded('sector')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}

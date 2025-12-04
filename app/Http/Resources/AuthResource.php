<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
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
            'image' => $this->image,
            'name' => $this->name,
            'password' => $this->password,
            'email' => $this->email,
            'address' => $this->address,
            'country' => $this->country,
            'profession' => $this->profession,
            'company_name' => $this->company_name,
            'code' => $this->code,
            'role_level' => $this->role_level,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => new RoleResource($this->whenLoaded('role')),
            'membership' => new MembershipResource($this->whenLoaded('membership')),
            'qrcode' => new QrCodeResource($this->whenLoaded('qrcode')),
        ];
     
    }
}

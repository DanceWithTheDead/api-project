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
            'user' => [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email
            ],
            'token' => $this->when($this->token, $this->token)
        ];
    }

    public function with(Request $request)
    {
        return [
          'success' => true,
          'message' => 'User created successfully.',
        ];
    }
}

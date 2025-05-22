<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
                'Id' => $this->id,
                'First Name' => $this->first_name,
                'Last Name' => $this->last_name,
                'Email' => $this->email,
                'Created' => $this->when(Route::currentRouteName() == 'user.info',
                    Carbon::parse($this->created_at)->format('d-m-Y H:i:s')),
                'Updated' => $this->when(\Route::currentRouteName() == 'user.update',
                    Carbon::parse($this->updated_at)->format('d-m-Y H:i:s'))

            ],
            'token' => $this->when($this->token, $this->token)
        ];
    }

}

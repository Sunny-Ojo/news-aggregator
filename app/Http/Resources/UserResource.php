<?php

namespace App\Http\Resources;

use App\Services\UserPreferenceService;
use Illuminate\Http\Request;
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'preferences' => $this->whenLoaded(
                'preferences',
                fn() => UserPreferenceResource::make($this->preferences),
                fn() => UserPreferenceResource::make($this->getOrCreatePreferences())
            ),
        ];
    }

    public function getOrCreatePreferences()
    {
        return app()->make(UserPreferenceService::class)->getUserPreferences($this->id);
    }

}

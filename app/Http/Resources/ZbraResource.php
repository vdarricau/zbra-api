<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZbraResource extends JsonResource
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
            'message' => $this->message,
            'sender' => FriendResource::make($this->sender()->getResults()),
            'receiver' => FriendResource::make($this->receiver()->getResults()),
            'createdAt' => $this->created_at,
        ];
    }
}

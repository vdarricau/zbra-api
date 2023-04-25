<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
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
            'friend' => FriendResource::make($this->friend()->getResults()),
            'message' => MessageResource::make($this->message()->getResults()),
            'countUnreadMessages' => $this->countUnreadMessages(),
            'updatedAt' => $this->updated_at,
        ];
    }
}

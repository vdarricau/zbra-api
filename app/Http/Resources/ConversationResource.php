<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User */
        $user = $request->user();

        $friend = $this->users()->where('id', '!=', $user->id)->first();

        return [
            'id' => $this->id,
            'friend' => FriendResource::make($friend),
            'messages' => MessageResource::collection($this->messages()->orderByDesc('created_at')->getResults()),
            // 'countUnreadMessages' => $this->countUnreadMessages(), /* @TODO implement that differently */
            'updatedAt' => $this->updated_at,
        ];
    }
}

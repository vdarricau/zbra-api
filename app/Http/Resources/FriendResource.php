<?php

namespace App\Http\Resources;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User */
        $user = auth()->user();
        $conversationId = null;

        if ($user && $user->isNot($this->resource)) {
            $conversationId = Conversation::findOneOnOne($user, $this->resource)?->id;
        }

        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'conversationId' => $conversationId,
        ];
    }
}

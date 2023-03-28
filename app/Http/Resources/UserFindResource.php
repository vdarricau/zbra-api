<?php

namespace App\Http\Resources;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFindResource extends JsonResource
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

        $friendRequest = FriendRequest::find($user, $this->resource)->get()->first();

        return [
            'id' => $this->id,
            'friendRequest' => $friendRequest ? new FriendRequestResource($friendRequest) : null,
            'user' => new FriendResource($this),
            'isFriend' => $user->isFriend($this->resource),
        ];
    }
}
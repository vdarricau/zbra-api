<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FriendsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        return new JsonResponse(FriendResource::collection($user->friends()->orderBy('created_at', 'DESC')->get()));
    }
}

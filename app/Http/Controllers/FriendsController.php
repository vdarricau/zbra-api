<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        $search = $request->query('search');

        $friends = $user->friends();

        if (is_string($search)) {
            $search = '%'.$search.'%';
            $friends = $friends->where('users.username', 'LIKE', $search);
        }

        return new JsonResponse(FriendResource::collection($friends->orderBy('users.username')->get()));
    }

    public function view(User $friend): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        /** @var User */
        $user = auth()->user();

        if (false === $user->isFriend($friend)) {
            return new JsonResponse([
                'error' => 'You are not zbros!',
            ], 403);
        }

        return new JsonResponse(new FriendResource($friend));
    }
}

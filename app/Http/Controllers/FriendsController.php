<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Http\Resources\ZbraResource;
use App\Models\User;
use App\Models\Zbra;
use Illuminate\Http\JsonResponse;

class FriendsController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        return new JsonResponse(FriendResource::collection($user->friends()->orderBy('friends.created_at', 'DESC')->get()));
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

    public function zbras(User $friend): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        if (false === $user->isFriend($friend)) {
            return new JsonResponse([
                'error' => 'You are not zbros!',
            ], 403);
        }

        return new JsonResponse(ZbraResource::collection(Zbra::getExchangedZbras($user, $friend)->get()));
    }
}

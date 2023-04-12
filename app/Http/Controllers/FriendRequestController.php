<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use App\Models\User;
use App\Notifications\NewFriendRequestNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        $filter = $request->query('filter', FriendRequest::PENDING);

        if ($filter === FriendRequest::REQUESTED) {
            $friendRequests = $user->requestedFriendRequests();
        } else {
            $friendRequests = $user->friendRequests();
            $user->unreadNotifications()->where('type', NewFriendRequestNotification::class)->update(['read_at' => now()]);
        }

        return new JsonResponse(FriendRequestResource::collection($friendRequests->get()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $friend): JsonResponse
    {
        /** @var User */
        $currentUser = auth()->user();

        if ($currentUser->is($friend)) {
            return new JsonResponse([
                'error' => 'You cannot be zbro with yourself',
            ], 400);
        }

        if (FriendRequest::exists($currentUser, $friend)) {
            return new JsonResponse([
                'error' => 'A zbro request already exists.',
            ], 400);
        }

        if ($currentUser->isFriend($friend)) {
            return new JsonResponse([
                'error' => 'You guys are already zbros!',
            ], 400);
        }

        $friendRequest = new FriendRequest();
        
        $friendRequest->requester()->associate($currentUser);
        $friendRequest->friendToBe()->associate($friend);

        $friendRequest->saveOrFail();

        $friend->notify(new NewFriendRequestNotification($friendRequest));

        return new JsonResponse(new FriendRequestResource($friendRequest), 201);
    }

    /**
     * @var FriendRequest $friendRequest
     */
    public function accept(FriendRequest $friendRequest): JsonResponse
    {        
        /** @var User */
        $user = auth()->user();

        Gate::inspect('view', $friendRequest);

        /** @var User */
        $newFriend = $friendRequest->requester()->getResults();
        
        $user->addFriend($newFriend);

        $friendRequest->delete();

        return new JsonResponse(null, 204);
    }
}

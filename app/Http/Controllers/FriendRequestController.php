<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use App\Models\User;
use App\Notifications\NewFriendRequestNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FriendRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        $filter = $request->query('filter', FriendRequest::PENDING);

        if ($filter === FriendRequest::REQUESTED) {
            $friendRequests = $user->requestedFriendRequests();
        } else {
            $friendRequests = $user->friendRequests()->where('status', FriendRequest::STATUS_SENT);
            $user->unreadNotifications()->where('type', NewFriendRequestNotification::class)->update(['read_at' => now()]);
        }

        return new JsonResponse(FriendRequestResource::collection($friendRequests->get()));
    }

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

        $friendRequest->sender()->associate($currentUser);
        $friendRequest->receiver()->associate($friend);

        $friendRequest->saveOrFail();

        $friend->notify(new NewFriendRequestNotification($friendRequest));

        return new JsonResponse(new FriendRequestResource($friendRequest), 201);
    }

    public function accept(FriendRequest $friendRequest): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        Gate::inspect('view', $friendRequest);

        /** @var User */
        $newFriend = $friendRequest->sender()->getResults();

        $user->addFriend($newFriend);

        $friendRequest->accept();

        return new JsonResponse(null, 204);
    }

    public function cancel(FriendRequest $friendRequest): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        Gate::inspect('view', $friendRequest);

        $friendRequest->cancel();

        return new JsonResponse(null, 204);
    }
}

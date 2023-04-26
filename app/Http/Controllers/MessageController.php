<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageCannotBeSentToNonFriendsException;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request): JsonResponse
    {
        // @TODO https://laravel.com/docs/10.x/rate-limiting

        /** @var string[] */
        $validatedParams = $request->validated();

        $message = $validatedParams['message'];
        $friendId = $validatedParams['friendId'];

        /** @var User */
        $user = auth()->user();

        /** @var User|null */
        $friend = User::find($friendId);

        if ($friend === null) {
            return new JsonResponse(['error' => 'Cannot find your zbro!'], 404);
        }

        try {
            $message = $user->sendMessage($friend, $message);
        } catch (MessageCannotBeSentToNonFriendsException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(new MessageResource($message), 201);
    }
}

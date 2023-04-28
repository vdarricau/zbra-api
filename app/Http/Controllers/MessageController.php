<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageCannotBeSentIfUserNotPartOfConversationException;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function index(Conversation $conversation): JsonResponse
    {
        Gate::authorize('view', $conversation);

        return new JsonResponse(MessageResource::collection($conversation->messages()->get()));
    }

    public function store(Conversation $conversation, StoreMessageRequest $request): JsonResponse
    {
        // @TODO https://laravel.com/docs/10.x/rate-limiting

        Gate::authorize('update', $conversation);

        /** @var string[] */
        $validatedParams = $request->validated();

        $message = $validatedParams['message'];

        /** @var User */
        $user = auth()->user();

        try {
            $message = $user->sendMessage($conversation, $message);
        } catch (MessageCannotBeSentIfUserNotPartOfConversationException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(new MessageResource($message), 201);
    }
}

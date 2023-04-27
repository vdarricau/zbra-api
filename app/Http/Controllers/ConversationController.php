<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        return new JsonResponse(ConversationResource::collection(
            $user->conversations()->orderByDesc('updated_at')->get()
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function view(Conversation $conversation): JsonResponse
    {
        Gate::authorize('view', $conversation);

        return new JsonResponse(new ConversationResource($conversation));
    }
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageCannotBeSentIfUserNotPartOfConversationException;
use App\Http\Requests\StoreZbraRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Zbra;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class ZbraController extends Controller
{
    public function store(StoreZbraRequest $request, Conversation $conversation): JsonResponse
    {
        // @TODO https://laravel.com/docs/10.x/rate-limiting

        Gate::authorize('update', $conversation);

        /** @var string[] */
        $validatedParams = $request->validated();

        $keyword = $validatedParams['keyword'];

        /** @var User */
        $user = auth()->user();

        /** @var \Illuminate\Http\Client\Response */
        $response = Http::baseUrl('https://api.giphy.com/v1')->get('/gifs/search', [
            'q' => $keyword,
            'api_key' => config('services.giphy.api_key'),
        ]);

        $zbra = new Zbra();
        $zbra->text = '#'.$keyword;
        $zbra->image_url ??= $response->json()['data'][0]['images']['fixed_width']['webp'];
        $zbra->image_height ??= $response->json()['data'][0]['images']['fixed_width']['height'];
        $zbra->image_width ??= $response->json()['data'][0]['images']['fixed_width']['width'];

        try {
            $message = $user->sendMessage($conversation, (new Message()), $zbra);
        } catch (MessageCannotBeSentIfUserNotPartOfConversationException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(new MessageResource($message), 201);
    }
}

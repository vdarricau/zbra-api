<?php

namespace App\Http\Controllers;

use App\Exceptions\ZbraCannotBeSentToNonFriendsException;
use App\Http\Requests\StoreZbraRequest;
use App\Http\Resources\ZbraResource;
use App\Models\User;
use App\Models\Zbra;
use Exception;
use Illuminate\Http\JsonResponse;

class ZbraController extends Controller
{
    public function store(StoreZbraRequest $request): JsonResponse
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
            $zbra = $user->sendZbra($friend, $message);
        } catch (ZbraCannotBeSentToNonFriendsException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(new ZbraResource($zbra), 201);
    }

    public function show(Zbra $zbra): JsonResponse
    {
        $this->authorize('view', $zbra);

        $zbra->status = Zbra::STATUS_READ;
        $zbra->save();

        return new JsonResponse($zbra);
    }
}

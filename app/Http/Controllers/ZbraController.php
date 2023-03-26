<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreZbraRequest;
use App\Http\Resources\ZbraResource;
use App\Models\User;
use App\Models\Zbra;
use Illuminate\Http\JsonResponse;

class ZbraController extends Controller
{
    /**
     * @var StoreZbraRequest $request
     * @return JsonResponse
     */
    public function store(StoreZbraRequest $request): JsonResponse
    {
        // @TODO https://laravel.com/docs/10.x/rate-limiting

        ['message' => $message, 'friendId' => $friendId] = $request->validated();

        /** @var User */
        $user = auth()->user();

        /** @var User */
        $friend = User::find($friendId);

        if ($user->isNot($friend) && false === $user->isFriend($friend)) {
            return new JsonResponse(['error' => 'You can only send Zbras to your Zbros']);
        }

        $zbra = new Zbra();

        $zbra->message = $message;
        $zbra->receiver()->associate($friend);
        $zbra->sender()->associate($user);

        $zbra->saveOrFail();

        return new JsonResponse(new ZbraResource($zbra), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Zbra $zbra): JsonResponse
    {
        $this->authorize('view', $zbra);

        $zbra->status = Zbra::STATUS_READ;
        $zbra->save();

        return new JsonResponse($zbra);
    }
}

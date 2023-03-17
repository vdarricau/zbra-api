<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreZbraRequest;
use App\Models\User;
use App\Models\Zbra;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ZbraController extends Controller
{
    /**
     * @var Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        $filter = $request->query('filter', Zbra::FILTER_RECEIVED);

        if ($filter === Zbra::FILTER_SENT) {
            $zbras = $user->sentZbras();
        } else {
            $zbras = $user->zbras();
        }

        return new JsonResponse($zbras->get());
    }

    /**
     * @var StoreZbraRequest $request
     * @return JsonResponse
     */
    public function store(StoreZbraRequest $request): JsonResponse
    {
        ['message' => $message, 'friend_id' => $friendId] = $request->validated();

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

        return new JsonResponse($zbra, 200);
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

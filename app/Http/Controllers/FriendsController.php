<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class FriendsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        return new JsonResponse($user->friends()->get());
    }
}
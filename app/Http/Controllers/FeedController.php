<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeedResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        return new JsonResponse(FeedResource::collection(
            $user->feeds()->orderByDesc('updated_at')->get()
        ));
    }
}

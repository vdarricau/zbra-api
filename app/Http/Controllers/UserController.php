<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Http\Resources\UserFindResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        $search = $request->query('search');

        if (
            null === $search ||
            false === is_string($search) ||
            strlen($search) <= 3
        ) {
            return new JsonResponse([]); // Not allowing to look through all the users, gotta search them!
        }

        $search = '%'.$search.'%';

        $users = User::where(function (Builder $query) use ($search) {
            $query
                ->where('username', 'LIKE', $search)
                ->orWhere('name', 'LIKE', $search);
        })
        ->where('id', '!=', $user->id)
        ->orderBy('username')
        ->get();

        return new JsonResponse(UserFindResource::collection($users));
    }
}
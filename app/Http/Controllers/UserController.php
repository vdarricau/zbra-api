<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');

        if (null === $search || strlen($search) <= 3) {
            return new JsonResponse([]); // Not allowing to look through all the users, gotta search them!
        }

        $search = '%'.$search.'%';

        $users = User::where('username', 'LIKE', $search)
            ->orWhere('name', 'LIKE', $search)
            ->get();

        return new JsonResponse(FriendResource::collection($users));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @var Request $request
     * @return Response 
     */
    public function register(Request $request): Response
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        /** @var User */
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return Response($response, 201);
    }

    /**
     * @var Request $request
     * @return Response 
     */
    public function login(Request $request): Response
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        /** @var User */
        $user = User::where('email', $fields['email'])->first();

        if (!$user || false === Hash::check($fields['password'], $user->getAuthPassword())) {
            return response([
                'message' => 'Bad creds',
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return Response($response, 201);
    }

    /**
     * @return
     */
    public function logout(): array
    {
        /** @var User */
        $user = auth()->user();
        
        $user->tokens()->delete();

        return [
            'message' => 'Logged out',
        ];
    }
}

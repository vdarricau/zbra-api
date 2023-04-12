<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFriendRequestNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function friendRequests(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        $notifications = $user->unreadNotifications()->where('type', NewFriendRequestNotification::class)->get();

        return new JsonResponse($notifications);
    }
}

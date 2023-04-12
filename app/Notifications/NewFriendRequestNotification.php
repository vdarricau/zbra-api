<?php

namespace App\Notifications;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFriendRequestNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(private FriendRequest $friendRequest) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        /** @var User */
        $friend = $this->friendRequest->sender()->getResults();

        return [
            'id' => $this->friendRequest->id,
            'friend' => [
                'id' => $friend->id,
                'username' => $friend->username,
            ],
        ];
    }
}

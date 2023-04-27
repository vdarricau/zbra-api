<?php

namespace App\Listeners;

use App\Events\FriendRequestAcceptedEvent;
use App\Models\Conversation;
use App\Models\User;

class FriendRequestAcceptedCreatedConversationListener
{
    /**
     * Handle the event.
     */
    public function handle(FriendRequestAcceptedEvent $event): void
    {
        $friendRequest = $event->friendRequest;

        /** @var User */
        $from = $friendRequest->sender()->getResults();

        /** @var User */
        $to = $friendRequest->receiver()->getResults();

        $conversation = new Conversation();
        $conversation->save();

        $conversation->users()->attach($from);
        $conversation->users()->attach($to);
    }
}

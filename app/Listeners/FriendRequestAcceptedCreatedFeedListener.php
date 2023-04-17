<?php

namespace App\Listeners;

use App\Events\FriendRequestAcceptedEvent;
use App\Models\Feed;
use App\Models\User;

class FriendRequestAcceptedCreatedFeedListener
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

        $feed = new Feed();
        $feed->user()->associate($from);
        $feed->friend()->associate($to);

        $feedSender = new Feed();
        $feedSender->user()->associate($to);
        $feedSender->friend()->associate($from);

        $feed->save();
        $feedSender->save();
    }
}

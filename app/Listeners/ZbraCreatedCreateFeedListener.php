<?php

namespace App\Listeners;

use App\Events\ZbraCreated;
use App\Models\Feed;
use App\Models\User;

class ZbraCreatedCreateFeedListener
{
    /**
     * Handle the event.
     */
    public function handle(ZbraCreated $event): void
    {
        $zbra = $event->zbra;

        /** @var User */
        $sender = $zbra->sender()->getResults();
        
        /** @var User */
        $receiver = $zbra->receiver()->getResults();
    
        $feed = Feed::where('user_id', $sender->id)->where('friend_id', $receiver->id)->first();
        $feedSender = Feed::where('user_id', $receiver->id)->where('friend_id', $sender->id)->first();

        if (null === $feed) {
            $feed = new Feed();
            $feed->user()->associate($sender);
            $feed->friend()->associate($receiver);
        }

        if (null === $feedSender) {
            $feedSender = new Feed();
            $feedSender->user()->associate($receiver);
            $feedSender->friend()->associate($sender);
        }

        $feed->zbra_id = $zbra->id;
        $feedSender->zbra_id = $zbra->id;
    
        $feed->save();
        $feedSender->save();
    }
}

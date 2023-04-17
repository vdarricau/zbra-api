<?php

namespace App\Listeners;

use App\Events\ZbraCreatedEvent;
use App\Models\Feed;
use App\Models\User;

class ZbraCreatedCreateFeedListener
{
    /**
     * @TODO test this shit
     * 
     * Handle the event.
     */
    public function handle(ZbraCreatedEvent $event): void
    {
        $zbra = $event->zbra;

        /** @var User */
        $sender = $zbra->sender()->getResults();
        
        /** @var User */
        $receiver = $zbra->receiver()->getResults();
    
        $feed = Feed::where('user_id', $sender->id)->where('receiver_user_id', $receiver->id)->first();
        $feedSender = Feed::where('user_id', $receiver->id)->where('receiver_user_id', $sender->id)->first();

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

        $feed->zbra()->associate($zbra);
        $feedSender->zbra()->associate($zbra);

        $feed->save();
        $feedSender->save();
    }
}

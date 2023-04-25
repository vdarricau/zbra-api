<?php

namespace App\Listeners;

use App\Events\MessageSentEvent;
use App\Models\Feed;
use App\Models\User;

class MessageCreatedCreateFeedListener
{
    /**
     * @TODO test this shit
     *
     * Handle the event.
     */
    public function handle(MessageSentEvent $event): void
    {
        $message = $event->message;

        /** @var User */
        $sender = $message->sender()->getResults();

        /** @var User */
        $receiver = $message->receiver()->getResults();

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

        $feed->message()->associate($message);
        $feedSender->message()->associate($message);

        $feed->save();
        $feedSender->save();
    }
}

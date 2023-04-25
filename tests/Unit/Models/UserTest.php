<?php

namespace Tests\Unit\Models;

use App\Exceptions\MessageCannotBeSentToNonFriendsException;
use App\Models\Feed;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class UserTest extends TestCase
{
    /////////////////////////////////////////////////////
    // Tests feeds()

    /**
     * @test
     */
    public function feeds_should_show_zbra_if_send(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $friendWithMessage = User::factory()->create();
        /** @var User */
        $friendNoMessage = User::factory()->create();

        $user->addFriend($friendWithMessage);
        $user->addFriend($friendNoMessage);

        self::assertEmpty($user->feeds()->get());

        $message = Message::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithMessage->id,
                'message' => 'zbra',
            ]);

        $message->setCreatedAt(Date::yesterday());
        $message->save();

        $mostRecentMessage = Message::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithMessage->id,
                'message' => 'most recent zbra',
            ]);

        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertFalse($feed->message()->is($message));
        self::assertTrue($feed->message()->is($mostRecentMessage));
    }

    /**
     * @test
     */
    public function feeds_should_show_zbra_if_received(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $friendWithMessage = User::factory()->create();
        /** @var User */
        $friendNoMessage = User::factory()->create();

        $user->addFriend($friendWithMessage);
        $user->addFriend($friendNoMessage);

        self::assertEmpty($user->feeds()->get());

        $message = Message::factory()
            ->create([
                'sender_user_id' => $friendWithMessage->id,
                'receiver_user_id' => $user->id,
                'message' => 'zbra',
            ]);

        $message->setCreatedAt(Date::yesterday());
        $message->save();

        $mostRecentMessage = Message::factory()
            ->create([
                'sender_user_id' => $friendWithMessage->id,
                'receiver_user_id' => $user->id,
                'message' => 'most recent zbra',
            ]);

        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertFalse($feed->message()->is($message));
        self::assertTrue($feed->message()->is($mostRecentMessage));
    }

    /**
     * @test
     */
    public function feeds_should_show_just_latest_if_send_received_by_same_user(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $friendWithMessage = User::factory()->create();
        /** @var User */
        $friendNoMessage = User::factory()->create();

        $user->addFriend($friendWithMessage);
        $user->addFriend($friendNoMessage);

        self::assertEmpty($user->feeds()->get());

        $message = Message::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithMessage->id,
                'message' => 'zbra',
            ]);

        $message->setCreatedAt(Date::yesterday());
        $message->save();

        $mostRecentMessage = Message::factory()
            ->create([
                'sender_user_id' => $friendWithMessage->id,
                'receiver_user_id' => $user->id,
                'message' => 'most recent zbra',
            ]);

        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertFalse($feed->message()->is($message));
        self::assertTrue($feed->message()->is($mostRecentMessage));
    }

    /**
     * @test
     */
    public function feeds_should_show_two_if_different_users(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $friendWithMessage = User::factory()->create();
        /** @var User */
        $anotherFriendWithMessage = User::factory()->create();

        $user->addFriend($friendWithMessage);
        $user->addFriend($anotherFriendWithMessage);

        self::assertEmpty($user->feeds()->get());

        $message = Message::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithMessage->id,
                'message' => 'zbra',
            ]);

        $message->setCreatedAt(Date::yesterday());
        $message->save();

        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertTrue($feed->message()->is($message));

        $mostRecentMessage = Message::factory()
            ->create([
                'sender_user_id' => $anotherFriendWithMessage->id,
                'receiver_user_id' => $user->id,
                'message' => 'most recent zbra',
            ]);

        $feeds = $user->feeds()->get();

        self::assertCount(2, $feeds);

        /** @var Feed */
        $feed = $feeds->shift();
        $second = $feeds->shift();

        self::assertTrue($feed->message()->is($message));
        self::assertTrue($second->message()->is($mostRecentMessage));
    }

    /**
     * @test
     */
    public function sendMessage_should_throw_exception_if_not_friends(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $notAFriend = User::factory()->create();

        $this->expectException(MessageCannotBeSentToNonFriendsException::class);

        $user->sendMessage($notAFriend, 'Messagelicious');
    }

    /**
     * @test
     */
    public function sendMessage_should_create_message(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $friend = User::factory()->create();

        $user->addFriend($friend);

        $message = $user->sendMessage($friend, 'Messagelicious');

        self::assertSame('Messagelicious', $message->message);
        self::assertTrue($user->is($message->sender()->getResults()));
        self::assertTrue($friend->is($message->receiver()->getResults()));
    }
}

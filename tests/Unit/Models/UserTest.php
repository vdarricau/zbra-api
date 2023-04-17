<?php

namespace Tests\Unit\Models;

use App\Exceptions\ZbraCannotBeSentToNonFriendsException;
use App\Models\User;
use App\Models\Zbra;
use App\Models\Feed;
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
        $friendWithZbra = User::factory()->create();
        /** @var User */
        $friendNoZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($friendNoZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithZbra->id,
                'message' => 'zbra',
            ])
        ;

        $zbra->setCreatedAt(Date::yesterday());
        $zbra->save();

        $mostRecentZbra = Zbra::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithZbra->id,
                'message' => 'most recent zbra',
            ])
        ;

        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertFalse($feed->zbra()->is($zbra));
        self::assertTrue($feed->zbra()->is($mostRecentZbra));
    }
    
    /**
     * @test
     */
    public function feeds_should_show_zbra_if_received(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $friendWithZbra = User::factory()->create();
        /** @var User */
        $friendNoZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($friendNoZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'sender_user_id' => $friendWithZbra->id,
                'receiver_user_id' => $user->id,
                'message' => 'zbra',
            ])
        ;

        $zbra->setCreatedAt(Date::yesterday());
        $zbra->save();

        $mostRecentZbra = Zbra::factory()
            ->create([
                'sender_user_id' => $friendWithZbra->id,
                'receiver_user_id' => $user->id,
                'message' => 'most recent zbra',
            ])
        ;
        
        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertFalse($feed->zbra()->is($zbra));
        self::assertTrue($feed->zbra()->is($mostRecentZbra));
    }

    /**
     * @test
     */
    public function feeds_should_show_just_latest_if_send_received_by_same_user(): void
    {
        /** @var User */
        $user = User::factory()->create();
        
        /** @var User */
        $friendWithZbra = User::factory()->create();
        /** @var User */
        $friendNoZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($friendNoZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithZbra->id,
                'message' => 'zbra',
            ])
        ;

        $zbra->setCreatedAt(Date::yesterday());
        $zbra->save();

        $mostRecentZbra = Zbra::factory()
            ->create([
                'sender_user_id' => $friendWithZbra->id,
                'receiver_user_id' => $user->id,
                'message' => 'most recent zbra',
            ])
        ;

        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertFalse($feed->zbra()->is($zbra));
        self::assertTrue($feed->zbra()->is($mostRecentZbra));
    }

    /**
     * @test
     */
    public function feeds_should_show_two_if_different_users(): void
    {
        /** @var User */
        $user = User::factory()->create();
        
        /** @var User */
        $friendWithZbra = User::factory()->create();
        /** @var User */
        $anotherFriendWithZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($anotherFriendWithZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $friendWithZbra->id,
                'message' => 'zbra',
            ])
        ;

        $zbra->setCreatedAt(Date::yesterday());
        $zbra->save();

        $feeds = $user->feeds()->get();

        self::assertCount(1, $feeds);

        /** @var Feed */
        $feed = $feeds->first();

        self::assertTrue($feed->zbra()->is($zbra));

        $mostRecentZbra = Zbra::factory()
            ->create([
                'sender_user_id' => $anotherFriendWithZbra->id,
                'receiver_user_id' => $user->id,
                'message' => 'most recent zbra',
            ])
        ;

        $feeds = $user->feeds()->get();

        self::assertCount(2, $feeds);

        /** @var Feed */
        $feed = $feeds->shift();
        $second = $feeds->shift();

        self::assertTrue($feed->zbra()->is($zbra));
        self::assertTrue($second->zbra()->is($mostRecentZbra));
    }

    /**
     * @test
     */
    public function sendZbra_should_throw_exception_if_not_friends(): void
    {
        /** @var User */
        $user = User::factory()->create();
        
        /** @var User */
        $notAFriend = User::factory()->create();

        $this->expectException(ZbraCannotBeSentToNonFriendsException::class);

        $user->sendZbra($notAFriend, 'Zbralicious');
    }

    /**
     * @test
     */
    public function sendZbra_should_create_zbra(): void
    {
        /** @var User */
        $user = User::factory()->create();
        
        /** @var User */
        $friend = User::factory()->create();

        $user->addFriend($friend);

        $zbra = $user->sendZbra($friend, 'Zbralicious');

        self::assertSame('Zbralicious', $zbra->message);
        self::assertTrue($user->is($zbra->sender()->getResults()));
        self::assertTrue($friend->is($zbra->receiver()->getResults()));
    }
}

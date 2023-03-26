<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Zbra;
use App\Models\Feed;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function should_show_zbra_if_send(): void
    {
        $user = User::factory()->create();

        $friendWithZbra = User::factory()->create();
        $friendNoZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($friendNoZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'user_sender_id' => $user->id,
                'user_receiver_id' => $friendWithZbra->id,
                'message' => 'zbra',
            ])
        ;

        $zbra->setCreatedAt(Date::yesterday());
        $zbra->save();

        $mostRecentZbra = Zbra::factory()
            ->create([
                'user_sender_id' => $user->id,
                'user_receiver_id' => $friendWithZbra->id,
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
    public function should_show_zbra_if_received(): void
    {
        $user = User::factory()->create();

        $friendWithZbra = User::factory()->create();
        $friendNoZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($friendNoZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'user_sender_id' => $friendWithZbra->id,
                'user_receiver_id' => $user->id,
                'message' => 'zbra',
            ])
        ;

        $zbra->setCreatedAt(Date::yesterday());
        $zbra->save();

        $mostRecentZbra = Zbra::factory()
            ->create([
                'user_sender_id' => $friendWithZbra->id,
                'user_receiver_id' => $user->id,
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
    public function should_show_just_latest_if_send_received_by_same_user(): void
    {
        $user = User::factory()->create();

        $friendWithZbra = User::factory()->create();
        $friendNoZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($friendNoZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'user_sender_id' => $user->id,
                'user_receiver_id' => $friendWithZbra->id,
                'message' => 'zbra',
            ])
        ;

        $zbra->setCreatedAt(Date::yesterday());
        $zbra->save();

        $mostRecentZbra = Zbra::factory()
            ->create([
                'user_sender_id' => $friendWithZbra->id,
                'user_receiver_id' => $user->id,
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
    public function should_show_two_if_different_users(): void
    {
        $user = User::factory()->create();

        $friendWithZbra = User::factory()->create();
        $anotherFriendWithZbra = User::factory()->create();

        $user->addFriend($friendWithZbra);
        $user->addFriend($anotherFriendWithZbra);

        self::assertEmpty($user->feeds()->get());

        $zbra = Zbra::factory()
            ->create([
                'user_sender_id' => $user->id,
                'user_receiver_id' => $friendWithZbra->id,
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
                'user_sender_id' => $anotherFriendWithZbra->id,
                'user_receiver_id' => $user->id,
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
}

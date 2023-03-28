<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\FriendRequest;
use Tests\TestCase;

class FriendRequestTest extends TestCase
{
    /////////////////////////////////////////////////////
    // Tests hasFriendRequest()
    public function hasFriendRequest_should_return_false_if_no_friend_request(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $futureFriend = User::factory()->create();

        /** @var User */
        $randomFriendRequestUser = User::factory()->create();

        FriendRequest::factory()->create([
            'requester_id' => $user->id,
            'friend_id' => $randomFriendRequestUser->id,
        ]);

        FriendRequest::factory()->create([
            'requester_id' => $randomFriendRequestUser->id,
            'friend_id' => $user->id,
        ]);

        self::assertFalse(FriendRequest::exists($user, $futureFriend));
        self::assertFalse(FriendRequest::exists($futureFriend, $user));
    }

    /**
     * @test
     */
    public function hasFriendRequest_should_return_true_if_user_is_requester(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $futureFriend = User::factory()->create();

        FriendRequest::factory()->create([
            'requester_id' => $user->id,
            'friend_id' => $futureFriend->id,
        ]);

        self::assertTrue(FriendRequest::exists($user, $futureFriend));
        self::assertTrue(FriendRequest::exists($futureFriend, $user));
    }

    /**
     * @test
     */
    public function hasFriendRequest_should_return_true_if_user_is_friendsToBe(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'name' => 'kunt',
        ]);

        /** @var User */
        $futureFriend = User::factory()->create();

        FriendRequest::factory()->create([
            'requester_id' => $futureFriend->id,
            'friend_id' => $user->id,
        ]);

        self::assertTrue(FriendRequest::exists($user, $futureFriend));
        self::assertTrue(FriendRequest::exists($futureFriend, $user));
    }

    /**
     * @test
     */
    public function hasFriendRequest_should_return_false_if_friends(): void
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $futureFriend = User::factory()->create();

        $user->addFriend($futureFriend);

        self::assertFalse(FriendRequest::exists($user, $futureFriend));
        self::assertFalse(FriendRequest::exists($futureFriend, $user));
    }
}
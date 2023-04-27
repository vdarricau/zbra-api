<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Database\Factories\FriendFactory;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    /**
     * @test
     */
    public function conversations_should_be_created_when_a_friend_is_added(): void
    {
        /** @var User */
        $user = User::factory()->create();

        self::assertEmpty($user->conversations()->get());

        $friend = FriendFactory::make($user);

        $conversations = $user->conversations();

        self::assertCount(1, $conversations->get());
        self::assertTrue($conversations->first()->users()->get()->contains($friend));
        self::assertTrue($conversations->first()->users()->get()->contains($user));
    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\User;
use Database\Factories\FriendFactory;
use LogicException;
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

    /**
     * @test
     */
    public function findOneOnOne_should_find_conversation_between_two_friends(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $friend = FriendFactory::make($user);

        $conversationOneOnOne = Conversation::findOneOnOne($user, $friend);

        self::assertNotNull($conversationOneOnOne);
        self::assertSame(2, $conversationOneOnOne->users()->count());
        self::assertTrue($conversationOneOnOne->users()->get()->contains($friend));
        self::assertTrue($conversationOneOnOne->users()->get()->contains($user));

        $multiplePeopleConversation = new Conversation();
        $multiplePeopleConversation->save();
        $anotherFriend = FriendFactory::make($user);
        $multiplePeopleConversation->users()->attach($user);
        $multiplePeopleConversation->users()->attach($friend);
        $multiplePeopleConversation->users()->attach($anotherFriend);

        self::assertSame($conversationOneOnOne->id, Conversation::findOneOnOne($user, $friend)->id);
    }

    /**
     * @test
     */
    public function findOneOnOne_should_throw_exception_with_same_user(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $this->expectException(LogicException::class);

        Conversation::findOneOnOne($user, $user);
    }
}

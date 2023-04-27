<?php

namespace Tests\Unit\Models;

use App\Exceptions\MessageCannotBeSentIfUserNotPartOfConversationException;
use App\Models\Conversation;
use App\Models\User;
use Database\Factories\FriendFactory;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function sendMessage_should_throw_exception_if_not_friends(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create();

        $this->expectException(MessageCannotBeSentIfUserNotPartOfConversationException::class);

        $user->sendMessage($conversation, 'Messagelicious');
    }

    /**
     * @test
     */
    public function sendMessage_should_create_message(): void
    {
        /** @var User */
        $user = User::factory()->create();

        FriendFactory::make($user);

        $conversation = $user->conversations()->first();

        $message = $user->sendMessage($conversation, 'Messagelicious');

        self::assertSame('Messagelicious', $message->message);
        self::assertTrue($user->is($message->sender()->getResults()));
        self::assertTrue($conversation->is($message->conversation()->getResults()));
    }
}

<?php

namespace Tests\Unit\Models;

use App\Exceptions\MessageCannotBeSentIfUserNotPartOfConversationException;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Zbra;
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

        $user->sendMessage($conversation, (new Message(['message' => 'Messagelicious'])));
    }

    /**
     * @test
     */
    public function sendMessage_should_create_message(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        FriendFactory::make($user);

        $conversation = $user->conversations()->first();

        $message = $user->sendMessage($conversation, (new Message(['message' => 'Messagelicious'])));

        self::assertSame('Messagelicious', $message->message);
        self::assertTrue($user->is($message->sender()->getResults()));
        self::assertTrue($conversation->is($message->conversation()->getResults()));
    }

    /**
     * @test
     */
    public function sendMessage_should_create_message_with_zbra(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        FriendFactory::make($user);

        $conversation = $user->conversations()->first();

        $zbra = new Zbra([
            'text' => 'test',
            'image' => 'http://test.com/image.webp',
        ]);

        $message = $user->sendMessage($conversation, (new Message(['message' => 'Messagelicious'])), $zbra);

        self::assertSame('Messagelicious', $message->message);
        self::assertTrue($user->is($message->sender()->getResults()));
        self::assertTrue($conversation->is($message->conversation()->getResults()));
        self::assertTrue($zbra->is($message->zbra()->getResults()));
    }
}

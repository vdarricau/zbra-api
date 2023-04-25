<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        $senderId = $this->message->sender()->getResults()->id;
        $receiverId = $this->message->receiver()->getResults()->id;

        return new PrivateChannel('messages.' . $senderId . '.' . $receiverId);
    }

    /**
     * @return array<string, MessageResource>
     */
    public function broadcastWith(): array
    {
        return [
            'data' => new MessageResource($this->message),
        ];
    }
}

<?php

namespace App\Events;

use App\Http\Resources\ZbraResource;
use App\Models\Zbra;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZbraSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Zbra $zbra)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        $senderId = $this->zbra->sender()->getResults()->id;
        $receiverId = $this->zbra->receiver()->getResults()->id;

        return new PrivateChannel('zbras.' . $senderId . '.' . $receiverId);
    }

    /**
     * @return array<string, ZbraResource>
     */
    public function broadcastWith(): array
    {
        return [
            'data' => new ZbraResource($this->zbra),
        ];
    }
}

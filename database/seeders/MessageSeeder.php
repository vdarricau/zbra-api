<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(UserSeeder::USER_ID);
        $conversation = $user->conversations()->first();

        Message::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'conversation_id' => $conversation->id,
                'message' => 'Zbralut',
            ]);

        Message::factory()
            ->create([
                'id' => '98b57318-0a21-46c3-80c9-913dc0591f5f',
                'sender_user_id' => UserSeeder::USER_ID,
                'conversation_id' => $conversation->id,
                'message' => 'Zbra va???',
            ]);

        Message::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'conversation_id' => $conversation->id,
                'message' => 'Tant que je zbra ça va lolmdr',
            ]);
    }
}

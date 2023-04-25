<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'receiver_user_id' => UserSeeder::USER_ID,
            ]);

        Message::factory()
            ->create([
                'id' => '98b57318-0a21-46c3-80c9-913dc0591f5f',
                'sender_user_id' => UserSeeder::USER_ID,
                'receiver_user_id' => UserSeeder::OTHER_USER_ID,
                'message' => 'Zbrooooo',
            ]);

        Message::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'receiver_user_id' => UserSeeder::USER_ID,
            ]);

        Message::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'receiver_user_id' => UserSeeder::USER_ID,
            ]);
    }
}

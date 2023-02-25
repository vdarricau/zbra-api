<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'id' => '35ff314e-1b05-4214-9cc3-b5ff924debbb',
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $friend = User::factory()->create([
            'name' => 'User Friend',
            'email' => 'friend@example.com',
        ]);

        $friendRequestUser = User::factory()->create([
            'name' => 'User Friend request',
            'email' => 'friend.request@example.com',
        ]);

        User::factory()->create([
            'id' => '988daadd-a5eb-4be5-bab7-07106b644de7',
            'name' => 'Friendless user',
            'email' => 'friendless@example.com',
        ]);

        $user->addFriend($friend);

        (new FriendRequest([
            'id' => '988daadd-a5eb-4be5-bab7-07106b644de7',
            'requester_id' => $user->id,
            'friend_id' => $friendRequestUser->id,
        ]))->save();
    }
}

<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const USER_ID = '35ff314e-1b05-4214-9cc3-b5ff924debbb';
    public const OTHER_USER_ID = '988dc77b-25cd-492e-8bbc-32ab6cbe79af';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'id' => self::USER_ID,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        /** @var User */
        $friend = User::factory()->create([
            'id' => self::OTHER_USER_ID,
            'name' => 'User Friend',
            'email' => 'friend@example.com',
        ]);

        /** @var User */
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

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
            'username' => 'valzouille',
            'email' => 'val@zbra.ninja',
            'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_25_vbh1vh.png',
        ]);

        /** @var User */
        $friend = User::factory()->create([
            'id' => self::OTHER_USER_ID,
            'name' => 'User Friend',
            'email' => 'friend@example.com',
            'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_35_cujygt.png',
        ]);

        User::factory()->create([
            'id' => '988daadd-a5eb-4be5-bab7-07106b644de7',
            'name' => 'Friendless user',
            'email' => 'friendless@example.com',
        ]);

        $user->addFriend($friend);

        (new FriendRequest([
            'id' => '988daadd-a5eb-4be5-bab7-07106b644de7',
            'sender_user_id' => $user->id,
            'receiver_user_id' => User::factory()->create()->id,
        ]))->save();

        (new FriendRequest([
            'sender_user_id' => User::factory()->create([
                'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_4_u7rkmh.png',
            ])->id,
            'receiver_user_id' => $user->id,
        ]))->save();

        (new FriendRequest([
            'sender_user_id' => User::factory()->create([
                'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_2_p2zind.png',
            ])->id,
            'receiver_user_id' => $user->id,
        ]))->save();
    }
}

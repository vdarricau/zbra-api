<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class FriendRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new FriendRequest([
            'id' => '988daadd-a5eb-4be5-bab7-07106b644de7',
            'sender_user_id' => UserSeeder::USER_ID,
            'receiver_user_id' => User::factory()->create([
                'username' => 'gaytan',
                'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_4_u7rkmh.png',
            ])->id,
        ]))->save();

        $friendRequest = (new FriendRequest([
            'sender_user_id' => User::factory()->create([
                'username' => 'sienalala',
                'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_7_a4wfao.png',
            ])->id,
            'receiver_user_id' => UserSeeder::USER_ID,
        ]));

        /* @TODO add notify into friend request post save event */
        $friendRequest->save();
        // $user->notify(new NewFriendRequestNotification($friendRequest));

        $anotherFriendRequest = (new FriendRequest([
            'sender_user_id' => User::factory()->create([
                'username' => 'mariegolade',
                'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_2_p2zind.png',
            ])->id,
            'receiver_user_id' => UserSeeder::USER_ID,
        ]));

        $anotherFriendRequest->save();
    }
}

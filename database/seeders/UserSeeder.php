<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\FriendFactory;
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

        FriendFactory::make($user, [
            'id' => self::OTHER_USER_ID,
            'username' => 'jojolerigolo',
            'name' => 'Edy Hean',
            'email' => 'edy@zbra.ninja',
            'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_12_fspo1z.png',
        ]);

        User::factory()->create([
            'id' => '988daadd-a5eb-4be5-bab7-07106b644de7',
            'username' => 'lopezenec',
            'avatar' => 'https://res.cloudinary.com/dqs1ue9ka/image/upload/v1682188531/default-avatars/Groupe_de_masques_6_insccf.png',
        ]);
    }
}

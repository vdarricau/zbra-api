<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zbra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZbraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Zbra::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'receiver_user_id' => UserSeeder::USER_ID,
            ])
        ;
        
        Zbra::factory()
            ->create([
                'id' => '98b57318-0a21-46c3-80c9-913dc0591f5f',
                'sender_user_id' => UserSeeder::USER_ID,
                'receiver_user_id' => UserSeeder::OTHER_USER_ID,
                'message' => 'Zbrooooo',
            ])
        ;

        Zbra::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'receiver_user_id' => UserSeeder::USER_ID,
            ])
        ;

        Zbra::factory()
            ->create([
                'sender_user_id' => UserSeeder::OTHER_USER_ID,
                'receiver_user_id' => UserSeeder::USER_ID,
            ])
        ;
    }
}

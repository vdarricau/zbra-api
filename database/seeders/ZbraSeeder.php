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
                'user_sender_id' => UserSeeder::OTHER_USER_ID,
                'user_receiver_id' => UserSeeder::USER_ID,
            ])
        ;
        
        Zbra::factory()
            ->create([
                'id' => '98b57318-0a21-46c3-80c9-913dc0591f5f',
                'user_sender_id' => UserSeeder::USER_ID,
                'user_receiver_id' => UserSeeder::OTHER_USER_ID,
                'message' => 'Zbrooooo',
            ])
        ;

        Zbra::factory()
            ->create([
                'user_sender_id' => UserSeeder::OTHER_USER_ID,
                'user_receiver_id' => UserSeeder::USER_ID,
            ])
        ;
    }
}

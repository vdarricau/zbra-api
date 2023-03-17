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
            ->for(User::factory(), 'sender')
            ->for(User::factory(), 'receiver')
            ->create([
                'message' => 'Zbrooooo',
            ])
        ;
    }
}

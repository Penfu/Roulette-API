<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Penfu',
            'email' => 'penfu@gmail.com',
            'email_verified_at' => now(),
            'balance' => 10000,
            'password' => Hash::make('root'),
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Knouille',
            'email' => 'knouille@gmail.com',
            'email_verified_at' => now(),
            'balance' => 5000,
            'password' => Hash::make('root'),
            'remember_token' => Str::random(10),
        ]);

        User::factory()->count(20)->create();
    }
}

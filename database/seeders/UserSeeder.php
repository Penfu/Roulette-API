<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('root'),
        ]);

        User::create([
            'name' => 'Knouille',
            'email' => 'knouille@gmail.com',
            'password' => Hash::make('root'),
        ]);
    }
}

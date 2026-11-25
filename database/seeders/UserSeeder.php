<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Alice', 'email' => 'alice@flipplay.test', 'password' => 'password']);
        User::create(['name' => 'Budi', 'email' => 'budi@flipplay.test', 'password' => 'password']);
    }
}

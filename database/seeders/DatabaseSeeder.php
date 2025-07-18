<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Status::create(['status_code' => 'Pending']);
        User::create(
            ['name' => 'Mlamli Lolwane', 'email' => 'mrlolwane96@gmail.com', 'passwprd' => 'testpassword']);
    }
}

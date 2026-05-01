<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            "id" => 1,
            "name" => "Sahne Medya",
            "email" => "admin@sahnemedya.com",
            "password" => Hash::make("D4380urcem6535!Laravel!.?"),
        ]);
    }
}

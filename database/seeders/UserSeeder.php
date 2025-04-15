<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        // Tạo 5 user đăng nhập bằng email & mật khẩu
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'provider' => 'local',
                'provider_id' => null,
            ]);
        }

        // Tạo 5 user đăng nhập bằng Google
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => null, // Không cần password
                'provider' => 'google',
                'provider_id' => $faker->unique()->uuid . '_google',
            ]);
        }

        // Tạo 5 user đăng nhập bằng Facebook
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => null, // Không cần password
                'provider' => 'facebook',
                'provider_id' => $faker->unique()->uuid . '_facebook',
            ]);
        }
    }
}

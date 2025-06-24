<?php

namespace Database\Seeders;

use App\Models\{User, Major, Role};
use App\Helpers\MainRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
          $faker = Faker::create();

        foreach (MainRole::role as $key => $value) {
            Role::create([
                'name'  => $key
            ]);
        }
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id'   => 1,
        ]);

        $user->roles()->attach($user['role_id']);

         for ($index = 0; $index < 14; $index++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role_id' => rand(3, 5), // Angka antara 1 dan 4
                'password' => bcrypt('password'), // default password
            ]);
        }

        $this->call([
            MajorSeeder::class
        ]);

    }
}

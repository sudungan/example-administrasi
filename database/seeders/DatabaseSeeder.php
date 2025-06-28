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
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MajorSeeder::class
        ]);

    }
}

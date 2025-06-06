<?php

namespace Database\Seeders;

use App\Models\{User, Major, Role};
use App\Helpers\MainRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (MainRole::mainRole as $key => $value) {
            Role::create([
                'name'  => $key
            ]);
        }
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id'   => 1,
        ]);





        $listMajor = [
            [
                'name'      => 'Akuntansi dan lembagan keuangan',
                'user_id'   => rand(1, 3),
            ],
            [
                'name'      => 'Rekayasa Perangkat Lunak',
                'user_id'   => rand(1, 3),
            ],
        ];

        Major::insert($listMajor);

    }
}

<?php

namespace Database\Seeders;

use App\Models\{User, Major, Role};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'first_name'    => 'Hasudungan',
            'last_name'     => 'Sitorus',
            'address'       => 'Batam',
            'phone_number'  => '081264626720'
        ]);

        Role::factory()->create([
            'name' => 'admin',
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

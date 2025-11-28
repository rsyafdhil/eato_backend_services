<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //     User::create(
        //         [
        //         'name' => 'Admin',
        //         'email' => 'admin@test.com',
        //         'password' => bcrypt('12345678')
        //         ]
        // );
        DB::table('users')->insert(
            [
                [
                    'name' => 'Admin',
                    'email' => 'admin@test.com',
                    'nomor_telefon' => '1111111111',
                    'password' => bcrypt('12345678'),
                    'role_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Rassya',
                    'email' => 'rasya@test.com',
                    'nomor_telefon' => '08817106612',
                    'password' => bcrypt('12345678'),
                    'role_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Saga',
                    'email' => 'saga@test.com',
                    'nomor_telefon' => '22222222222',
                    'password' => bcrypt('12345678'),
                    'role_id' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Daffa',
                    'email' => 'daffa@test.com',
                    'nomor_telefon' => '33333333333',
                    'password' => bcrypt('12345678'),
                    'role_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }
}

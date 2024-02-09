<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'traveling@api.com',
            'password' => bcrypt('password'),
            'roleId' => 'baf18948-721e-49f5-aaf7-bed1a5415cb6'
        ]);

        User::create([
            'email' => 'johnvich@api.com',
            'password' => bcrypt('password'),
            'roleId' => '9442703c-dd4f-4e36-9954-a60574c408be'
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => 'baf18948-721e-49f5-aaf7-bed1a5415cb6', 
            'name' => 'admin'
        ]);

        Role::create([
            'id' => '9442703c-dd4f-4e36-9954-a60574c408be', 
            'name' => 'editor'
        ]);
    }
}

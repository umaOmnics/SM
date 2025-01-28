<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('resources')->insert([
            'name' => 'Users',
            'slug' => 'users',
        ]);
        DB::table('resources')->insert([
            'name' => 'Roles and Permissions',
            'slug' => 'roles-and-permissions',
        ]);
        DB::table('resources')->insert([
            'name' => 'Students',
            'slug' => 'students',
        ]);
        DB::table('resources')->insert([
            'name' => 'Employees',
            'slug' => 'employees',
        ]);
    }
}

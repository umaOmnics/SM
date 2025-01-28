<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Production
        DB::table('users')->insert([
            'salutations_id' => 1,
            'first_name' => 'cloud',
            'last_name' => 'Manager',
            'email' => 'info@info.in',
            'password' => '$2y$10$AY68uizPGBSNymbPIigZoOhGY7xe5A1PTtyLXABqPBoPk.0G7eOqu',
            'username' => 'info@info.in',
            'sys_admin'=>1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    } // End function

} // End class

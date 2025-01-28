<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class SalutationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ENGLISH
        if (App::isLocale('en')) {

            DB::table('salutations')->insert([
                'name' => 'Mr',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            DB::table('salutations')->insert([
                'name' => 'Mrs',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            DB::table('salutations')->insert([
                'name' => 'Miss',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

    } // End function

} // End class

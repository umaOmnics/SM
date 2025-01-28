<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class TitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ENGLISH
        if (App::isLocale('en')) {

            DB::table('titles')->insert([
                'name' => 'Dr.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('titles')->insert([
                'name' => 'Prof.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('titles')->insert([
                'name' => 'Prof. Dr.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        }

    } // End function
}

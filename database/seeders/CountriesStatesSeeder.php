<?php

namespace Database\Seeders;

use File;
use App\Models\CountriesStates;
use Illuminate\Database\Seeder;
class CountriesStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CountriesStates::truncate();

        $jsonStates = File::get("database/data/states.json");
        $states = json_decode($jsonStates);

        foreach ($states as $key => $value) {

            CountriesStates::create([
                "country_id" => $value->country_id,
                "name" => $value->name,
                "state_code" => $value->state_code,
                "latitude" => $value->latitude,
                "longitude" => $value->longitude
            ]);

        }

    }

}

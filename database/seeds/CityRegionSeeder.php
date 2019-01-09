<?php

use Illuminate\Database\Seeder;

use App\Models\City;
use App\Models\Region;

class CityRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Region::class, 10)->create();
        factory(City::class, 10)->create();
    }
}

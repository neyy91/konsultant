<?php

use Illuminate\Database\Seeder;

use App\Models\Education;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Education::class, 5)->create();
    }
}

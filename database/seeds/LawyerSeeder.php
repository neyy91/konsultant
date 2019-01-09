<?php

use Illuminate\Database\Seeder;

use App\Models\Lawyer;

class LawyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Lawyer::class, 5)->create();
    }
}

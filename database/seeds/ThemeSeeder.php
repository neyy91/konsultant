<?php

use Illuminate\Database\Seeder;

use App\Models\Theme;


class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Theme::class, 10)->create();
    }
}

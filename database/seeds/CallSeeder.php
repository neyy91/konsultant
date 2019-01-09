<?php

use Illuminate\Database\Seeder;

use App\Models\Call;

class CallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Call::class, 30)->create();
    }
}

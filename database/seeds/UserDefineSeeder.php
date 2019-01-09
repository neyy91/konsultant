<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class UserDefineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 'lawyer', 5)->create();
        factory(User::class, 'user', 5)->create();
    }
}

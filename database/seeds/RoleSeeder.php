<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, Role::ADMIN, 2)->create();
        factory(Role::class, Role::ADMIN, 2)->create();
    }
}

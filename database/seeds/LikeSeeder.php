<?php

use Illuminate\Database\Seeder;

use App\Models\Like;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Like::class, 'to_answers', 50)->create();
    }
}

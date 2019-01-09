<?php

use Illuminate\Database\Seeder;

use App\Models\Bookmark;
use App\Models\BookmarkCategory;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Смешные', 'Часто спрашивают', 'Отвечу позже'] as $category) {
            BookmarkCategory::create(['name' => $category]);
        }
        // factory(Bookmark::class, 20)->create();
    }
}

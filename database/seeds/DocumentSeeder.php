<?php

use Illuminate\Database\Seeder;

use App\Models\Document;
use App\Models\Answer;
use App\Models\Clarify;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Document::class, 30)->create();
    }
}

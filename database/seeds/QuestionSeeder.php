<?php

use Illuminate\Database\Seeder;

use App\Models\Question;
use App\Libs\FileHelper;

class QuestionSeeder extends Seeder
{
    use FileHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Question::class, 30)->create();

    }
}

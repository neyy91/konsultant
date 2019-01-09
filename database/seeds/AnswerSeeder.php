<?php

use Illuminate\Database\Seeder;

use App\Models\Answer;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Answer::class, 'to_questions', 30)->create();
        factory(Answer::class, 'to_documents', 30)->create();
        factory(Answer::class, 'to_calls', 30)->create();
    }
}

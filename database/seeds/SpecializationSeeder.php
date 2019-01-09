<?php

use Illuminate\Database\Seeder;

use App\Models\Lawyer;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Specialization;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lawyers = Lawyer::take(10)->get()->all();
        foreach ($lawyers as $lawyer) {
            $count = 5;
            foreach ($lawyer->answers()->where('to_type', Question::MORPH_NAME)->take(100)->get() as $answer) {
                $question = $answer->to;
                $categoryLaw = $question->categoryLaw;
                if (Specialization::where('category_law_id', $categoryLaw->id)->where('lawyer_id', $lawyer->id)->count() == 0) {
                    $spec = new Specialization();
                    $spec->category_law_id = $categoryLaw->id;
                    $spec->lawyer_id = $lawyer->id;
                    $spec->save();
                    $count--;
                }
                if ($count <= 0) {
                    break;
                }
            }
        }
    }
}

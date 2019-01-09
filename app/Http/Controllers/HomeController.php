<?php

namespace App\Http\Controllers;

use \Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\QuestionRepository;
use App\Models\CategoryLaw;
use App\Models\Question;
use App\Models\Lawyer;


/**
 * Главная страница.
 */
class HomeController extends Controller
{

    /**
     * Репозиторий вопросов.
     * @var QuestionRepository
     */
    protected $questions;


    /**
     * Контролер.
     *
     * @param QuestionRepository $questions
     * @return void
     */
    public function __construct(QuestionRepository $questions)
    {
        $this->questions = $questions;
    }

    protected function blockCategoriesLawThemes()
    {
        return view('home.categories', [
            'categories' => CategoryLaw::with('childs', 'childs.themes')->orderBy('from', 'asc')->orderBy('name', 'asc')->get()->all(),
        ]);
    }

    protected function blockQuestions()
    {
        return view('home.questions', [
            'questions' => $this->questions->take(config('site.question.page', 10)),
            'count' => Question::published()->count(),
        ]);
    }

    public function blockLawyers()
    {
        return view('home.lawyers', [
            'lawyers' => Lawyer::active()->with('user', 'user.city', 'user.photo')->take(10)->get(),
            'count' => Lawyer::active()->count(),
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }
        $questions = $this->questions->take(config('site.question.page', 10));
        return view('home', [
            'blocks' => [
                'categories' => $this->blockCategoriesLawThemes(),
                'questions' => $this->blockQuestions(),
                'lawyers' => $this->blockLawyers(),
            ]
        ]);
    }


}

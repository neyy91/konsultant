<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Illuminate\Http\Request;

use App\Http\Requests\User\LawyerAdminUpdateRequest;
use App\Http\Requests\Site\ThankingRequest;
use App\Repositories\QuestionRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\CallRepository;
use App\Repositories\AnswerRepository;
use App\Repositories\CityRepository;
use App\Repositories\LikeRepository;
use App\Repositories\UserRepository;
use App\Repositories\CategoryLawRepository;
use App\Models\CategoryLaw;
use App\Models\City;
use App\Models\Lawyer;
use App\Models\Pay;
use App\Models\User;


/**
 * Юристы.
 */
class LawyerController extends Controller
{

    /**
     * Репозиторий пользователей.
     * @var UserRepository
     */
    protected $users;

    /**
     * Репозиторий городов.
     * @var CityRepository
     */
    protected $cities;

    /**
     * Репозиторий категории.
     * @var CategoryLawRepository
     */
    protected $categories;

    /**
     * Репозиторий ответов.
     * @var AnswerRepository
     */
    protected $answers;

    /**
     * Репозиторий оценок.
     * @var LikeRepository
     */
    protected $likes;

    /**
     * Конструктор.
     * @param QuestionRepository $question
     * @return void
     */
    public function __construct(UserRepository $users, CityRepository $cities, CategoryLawRepository $categories, AnswerRepository $answers, LikeRepository $likes)
    {
        $this->users = $users;
        $this->cities = $cities;
        $this->categories = $categories;
        $this->answers = $answers;
        $this->likes = $likes;

        $this->middleware('auth')->except(['lawyers', 'profile']);
    }

    public function lawyers(CategoryLaw $category, City $city)
    {
        $title = trans('lawyer.all_lawyers');
        $template = 'lawyer.lawyers_filter';
        if ($city->id && $category->id) {
            $title = trans('lawyer.all_lawyers_category_city', ['city' => $city->name, 'category' => $category->name]);
        }
        elseif ($city->id) {
            $title = trans('lawyer.all_lawyers_city', ['city' => $city->name]);
        }
        elseif($category->id) {
            $title = trans('lawyer.all_lawyers_category', ['category' => $category->name]);
        }
        else {
            $template = 'lawyer.lawyers';
        }

        return view($template, [
            'breadcrumbs' => null,
            'lawyers' => $this->users->paginateLawyers($city->id, $category->id),
            'categories' => $this->categories->parentsWithChilds(2),
            'cities' => $this->cities->take(15),
            'user' => Auth::user(),
            'title' => $title,
            'city' => $city->id ? $city : null,
            'category' => $category->id ? $category : null,
        ]);
    }

    /**
     * Информация об отзывах.
     * @param  Lawyer $lawyer
     * @return array
     */
    protected function getLikedInfo(Lawyer $lawyer)
    {
        return [
            'count' => [
                'all' => $this->likes->countLikedLawyerQuestionAnswer($lawyer),
                'like' => $this->likes->countLikedLawyerQuestionAnswer($lawyer, 1),
            ],
        ];
    }

    /**
     * Профиль юриста.
     * @param  Request $request
     * @param  Lawyer $lawyer
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request, Lawyer $lawyer)
    {
        if ($request->input('payed')) {
            return redirect()->route('lawyer', ['lawyer' => $lawyer])->with('success', trans('pay.message.payment_success'));
        } elseif($request->input('failed')) {
            return redirect()->route('lawyer', ['lawyer' => $lawyer])->with('error', trans('pay.message.failed'));
        }

        return view('lawyer.profile', [
            'lawyer' => $lawyer->load('specializations.parent'),
            'user' => $lawyer->user,
            'liked' => [
                'count' => $this->getLikedInfo($lawyer)['count'],
                'latest' => $this->likes->latestLikedLawyerQuestionAnswer($lawyer),
            ],
            'questions' => [
                'count' => $this->answers->countLawyerAnsweredQuestions($lawyer),
                'take' => $this->answers->takeLawyerAnsweredQuestions($lawyer),
            ],
            'answerRepository' => $this->answers,
        ]);
    }

    /**
     * Страница отзывов.
     * @param  Lawyer $lawyer
     * @return \Illuminate\Http\Response
     */
    public function liked(Lawyer $lawyer)
    {
        $user = Auth::user();

        return view('lawyer.feedbacks', [
            'lawyer' => $lawyer,
            'user' => $lawyer->user,
            'liked' => $lawyer->liked()->paginate(10),
            'likedInfo' => $this->getLikedInfo($lawyer),
        ]);
    }

    /**
     * Вопросы.
     * @param  Lawyer $lawyer
     * @return \Illuminate\Http\Response
     */
    public function questions(Lawyer $lawyer)
    {
        return view('lawyer.questions', [
            'lawyer' => $lawyer,
            'user' => $lawyer->user,
            'total' => $this->answers->countLawyerAnsweredQuestions($lawyer),
            'questions' => $this->answers->paginateLawyerAnsweredQuestions($lawyer),
            'liked' => $this->getLikedInfo($lawyer),
        ]);
    }

    /**
     * Благодарность юристу.
     * @param  ThankingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function thanking(ThankingRequest $request)
    {
        $sum = $request->input('sum');
        $lawyer = Lawyer::findOrFail($request->input('lawyer'));
        if (User::where('email', $request->input('email'))->count() !== 0) {
            $request->session()->flash('warning', trans('user.message.email_exist_auth'));
            return redirect()->route('login');
        }

        if (Auth::guest()) {
            $userData = $request->only(['firstname', 'email']);
            $userData['password'] = str_random(6);
            $user = User::create($userData);

            Mail::to($user)->send(new Registered($user, ['password' => $userData['password']]));
        } else {
            $user = Auth::user();
        }

        $pay = new Pay();
        $pay->status = Pay::STATUS_START;
        $pay->service()->associate($lawyer);
        $pay->user()->associate($user);
        $pay->cost = $sum;
        $pay->save();

        return redirect()->route('lawyers.pay', ['pay' => $pay]);
    }

    /**
     * Оплата юристу.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function pay($id)
    {
        $pay = Pay::findOrFail($id);
        if (in_array($pay->status, [Pay::STATUS_PAYED, Pay::STATUS_SUCCESS])) {
            return redirect()->route('lawyer', ['lawyer' => $pay->service])->with('warning', trans('pay.message.payed'));
        }

        return view('lawyer.pay', [
            'pay' => $pay,
        ]);
    }

    /**
     * Обновление данных юриста.
     * @param  LawyerAdminUpdateRequest $request
     * @param  Lawyer                   $lawyer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(LawyerAdminUpdateRequest $request, Lawyer $lawyer)
    {
        if (!Hash::check($request->input('password'), Auth::user()->getAuthPassword())) {
            return redirect()->route('user.update.admin', ['user' => $lawyer->user])->with('danger', trans('user.message.password_wrong'));
        }
        
        $lawyer->fill($request->except('password'));
        $lawyer->expert = $request->input('expert') ? true : false;
        $lawyer->companyowner = $request->input('companyowner') ? true : false;
        $lawyer->save();

        return redirect()->route('user.update.admin', ['user' => $lawyer->user, 'tab' => 'lawyer'])->with('success', trans('user.message.lawyer_updated'));
    }

}

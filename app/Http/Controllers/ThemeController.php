<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Helper;
use App\Repositories\CategoryLawRepository;
use App\Repositories\QuestionRepository;
use App\Http\Requests;
use App\Http\Requests\Theme\ThemeRequest;
use App\Http\Requests\Theme\ThemesAdminFilterRequest;
use App\Models\CategoryLaw;
use App\Models\Theme;


/**
 * Контролер темы.
 */
class ThemeController extends Controller
{
    /**
     * Репозиторий категории права.
     * @var CategoryLawRepository
     */
    protected $categoryLaws;

    /**
     * Репозиторий вопросов.
     * @var QuestionRepository
     */
    protected $questions;

    /**
     * Конструктор.
     * @param CategoryLawRepository $categoryLaw
     */
    public function __construct(CategoryLawRepository $categoryLaws, QuestionRepository $questions)
    {
        $this->categoryLaw = $categoryLaws;
        $this->questions = $questions;
    }

    /**
     * Получение переменных для формы.
     * @return void
     */
    protected function getFormVars()
    {
        $formVars = [];
        $formVars['category_law_list'] = $this->categoryLaw->getList();
        $formVars['statuses'] = Theme::getStatuses();
        return $formVars;
    }

    /**
     * Список тем.
     * @return \Illuminate\Http\Response
     */
    public function themes()
    {
        $themes = Theme::orderBy('sort', 'asc')->get()->all();
        return view('theme.index', [
            'themes' => $themes,
        ]);
    }

    /**
     * Отображение страницы тема.
     * @param  Theme $theme
     * @return \Illuminate\Http\Response
     */
    public function view(Theme $theme)
    {
        if (!Auth::check() && $theme->status == Theme::UNPUBLISHED) {
            abort(404);
        }
        $this->authorize('view', $theme);

        return view('theme.view', [
            'theme' => $theme,
            'questions' => $this->questions->takeByTheme($theme),
        ]);
    }


    /**
     * Переадресация на темы.
     * @param  intger $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToTheme($id)
    {
        $theme = Theme::findOrFail($id);
        return redirect()->route('theme.view', ['theme' => $theme]);
    }


    /**
     * Список тем. Для администраторов.
     * @param ThemesAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function themesAdmin(ThemesAdminFilterRequest $request)
    {
        $fields = ['name', 'category_law_id', 'status'];
        $themes = Helper::getRequestModel(
            Theme::class,
            $request->all(),
            $fields,
            [
                'name' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ]
        );
        $themes->with('categoryLaw');

        return view('theme.admin.index', [
            'themes' => $themes->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Форма создания темы.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createFormAdmin(Request $request)
    {
        if (!$request->old()) {
            session()->flashInput(['status' => 1, 'autoslug' => 1]);
        }
        return view('theme.admin.create', ['formVars' => $this->getFormVars()]);
    }


    /**
     * Создания темы.
     * @param  ThemeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdmin(ThemeRequest $request)
    {
        $categoryLaw = CategoryLaw::find($request->input('category_law_id'));
        if ($categoryLaw) {
            $theme = $categoryLaw->themes()->create($request->all());
        }
        else {
            $theme = Theme::create($request->all());
        }

        return redirect()->route('theme.update.form.admin', ['id' => $theme->id])->with('success', trans('theme.message.created'));
    }


    /**
     * Отображение формы обновления темы.
     * @param  Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        return view('theme.admin.update', ['formVars' => $this->getFormVars(), 'theme' => $theme]);
    }


    /**
     * Обновление темы.
     * @param  CategoryLawRequest $request
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(ThemeRequest $request, $id)
    {
        $theme = Theme::findOrFail($id);
        $fields = $request->all();
        if ($request->input('autoslug')) {
            $fields['slug'] = $theme->generateSlug($fields);
        }
        // Обновление категории права
        $categoryLaw = CategoryLaw::findOrFail($request->category_law_id);
        if ($categoryLaw) {
            $theme->categoryLaw()->associate($categoryLaw);
        }
        else {
            $theme->categoryLaw()->dissociate();
        }
        $theme->update($fields);
        return redirect()->route('theme.update.form.admin', ['id' => $theme->id])->with('success', trans('question.message.updated'));
    }


    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $theme = Theme::findOrFail($id);
        return view('theme.admin.delete', ['theme' => $theme]);
    }

    /**
     * Удаление темы.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->delete();
        return redirect()->route('themes.admin')->with('warning', trans('theme.message.deleted'));
    }
}

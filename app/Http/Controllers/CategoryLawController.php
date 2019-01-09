<?php

namespace App\Http\Controllers;

use Gate;
use Auth;
use Illuminate\Http\Request;

use App\Helper;
use App\Http\Requests;
use App\Repositories\CategoryLawRepository;
use App\Repositories\QuestionRepository;
use App\Http\Requests\CategoryLaw\CategoryLawRequest;
use App\Http\Requests\CategoryLaw\CategoriesLawAdminFilterRequest;
use App\Models\CategoryLaw;


/**
 * Контроллер категории права.
 */
class CategoryLawController extends Controller
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
     * @param CategoryLawRepository $categoryLaws
     * @param QuestionRepository $questions
     */
    public function __construct(CategoryLawRepository $categoryLaws, QuestionRepository $questions)
    {
        $this->categoryLaws = $categoryLaws;
        $this->questions = $questions;
    }

    /**
     * Получение переменных для формы.
     * @return array
     */
    protected function getFormVars()
    {
        $formVars = [];
        $formVars['parent_list'] = $this->categoryLaws->getList();
        $formVars['statuses'] = CategoryLaw::getStatuses();
        $formVars['sort'] = CategoryLaw::max('sort') + 100;
        return $formVars;
    }

    /**
     * Список категории права.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request)
    {
        if ($request->has('status')) {
            $options['status'] = (int) $request->input('status');
        }
        else {
            $options['status'] = null;
        }
        $categories = $this->categoryLaws->parentsWithChilds(config('admin.categories.level', 3));
        return view('category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Отображение категории права.
     * @param  CategoryLaw $category
     * @return \Illuminate\Http\Response
     */
    public function view(CategoryLaw $category)
    {
        if (!Auth::check() && $category->status == CategoryLaw::UNPUBLISHED) {
            abort(404);
        }
        $this->authorize('view', $category);

        return view('category.view', [
            'categoryLaw' => $category,
            'questions' => $this->questions->takeByCategory($category, config('site.question.take', 5)),
        ]);
    }

    /**
     * Переадресация на страницу категории права.
     * @param  intger $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToCategoryLaw($id)
    {
        $categoryLaw = CategoryLaw::findOrFail($id);
        return redirect()->route('category.view', ['category' => $categoryLaw]);
    }


    /**
     * Список категории для администратора.
     * @param  CategoriesLawAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function categoriesAdmin(CategoriesLawAdminFilterRequest $request)
    {
        $fields = ['id', 'name', 'parent_id', 'status'];
        $categoriesLaw = Helper::getRequestModel(
            CategoryLaw::class,
            $request->all(),
            $fields,
            [
                'name' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ]
        );
        $categoriesLaw->with('parent');

        return view('category.admin.index', [
            'categoriesLaw' => $categoriesLaw->paginate(config('admin.categories.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Форма создания категории права.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createFormAdmin(Request $request)
    {
        return view('category.admin.create', [
            'formVars' => $this->getFormVars(),
        ]);
    }


    /**
     * Создания катгории права.
     * @param  CategoryLawRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdmin(CategoryLawRequest $request)
    {
        $parent = CategoryLaw::find($request->input('parent_id'));
        if ($parent) {
            $categoryLaw = $parent->childs()->create($request->all());
        }
        else {
            $categoryLaw = CategoryLaw::create($request->all());
        }
        return redirect()->route('category.update.form', ['id' => $categoryLaw->id]);
    }


    /**
     * Отображение формы обновления категории права.
     * @param  Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin(Request $request, $id)
    {
        $categoryLaw = CategoryLaw::findOrFail($id);
        return view('category.admin.update', ['formVars' => $this->getFormVars(), 'categoryLaw' => $categoryLaw]);
    }


    /**
     * Обновление категории права.
     * @param  CategoryLawRequest $request
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(CategoryLawRequest $request, $id)
    {
        $categoryLaw = CategoryLaw::findOrFail($id);
        $fields = $request->all();
        if ($request->input('autoslug')) {
            $fields['slug'] = $categoryLaw->generateSlug($fields);
        }
        // Обновление родителя
        $parent = CategoryLaw::findOrFail($request->parent_id);
        if ($parent) {
            $categoryLaw->parent()->associate($parent);
        }
        else {
            $categoryLaw->parent()->dissociate();
        }
        $categoryLaw->update($fields);
        return redirect()->route('category.update.form', ['id' => $categoryLaw->id])->with('success', trans('category.message.updated'));
    }


    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $categoryLaw = CategoryLaw::findOrFail($id);
        return view('category.admin.delete', ['categoryLaw' => $categoryLaw]);
    }

    /**
     * Удаление категории.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $categoryLaw = CategoryLaw::findOrFail($id);
        if ($categoryLaw->childs() || $categoryLaw->questions()) {
            return redirect()->route('category.delete.form', ['id' => $categoryLaw->id])->with('warning', trans('category.message.childs_exists'));
        }
        $categoryLaw->delete();
        return redirect()->route('categories.admin')->with('success', trans('category.message.deleted'));
    }

}

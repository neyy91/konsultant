<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Http\Requests\Page\PagesAdminFilterRequest;
use App\Http\Requests\Page\PageAdminRequest;
use App\Helper;
use App\Models\Page;


/**
 * Страницы.
 */
class PageController extends Controller
{

    public function getFormVars()
    {
        $formVars = [];
        $formVars['statuses'] = Page::getStatuses();
        $formVars['layouts'] = Page::getLyoutsList();
        $formVars['page_layouts'] = Page::getPageLayoutsList();

        return $formVars;
    }

    public function view(Page $page)
    {
        if (! (View::exists("layouts.{$page->layout}") || View::exists("layouts.page.{$page->page_layout}")) ) {
            abort(404);
        }
        return view('page.view', [
            'page' => $page,
        ]);
    }

    /**
     * Список страниц.
     * @param PagesAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function pagesAdmin(PagesAdminFilterRequest $request)
    {
        $fields = ['id', 'title', 'status', 'layout', 'page_layout'];
        $pages = Helper::getRequestModel(
            Page::class,
            $request->all(),
            $fields,
            [
                'title' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ],
            ['order' => ['title', 'desc']]
        );

        return view('page.admin.index', [
            'pages' => $pages->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'request' => $request,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Форма создания страницы.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createFormAdmin()
    {
        return view('page.admin.create', [
            'formVars' => $this->getFormVars(),
        ]);
    }


    /**
     * Создания катгории права.
     * @param  PageAdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdmin(PageAdminRequest $request)
    {
        $page = new Page($request->all());
        $page->user()->associate(Auth::user())->save();

        return redirect()->route('page.update.form.admin', ['id' => $page->id ])->with('success', trans('page.message.created'))->with('updating', true);
    }

    /**
     * Отображение формы обновления страницы.
     * @param  Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        return view('page.admin.update', [
            'formVars' => $this->getFormVars(),
            'page' => $page
        ]);
    }

    /**
     * Обновление страницы.
     * @param  PageAdminRequest $request
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(PageAdminRequest $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->update($request->all());

        return redirect()->route('page.update.form.admin', ['id' => $page->id])->with('success', trans('page.message.updated'))->with('updating', true);
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $page = Page::findOrFail($id);
        return view('page.admin.delete', ['page' => $page]);
    }

    /**
     * Удаление страницы.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('pages.admin')->with('warning', trans('page.message.deleted'))->with('updating', true);
    }

}

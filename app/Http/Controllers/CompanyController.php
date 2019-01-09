<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helper;
use App\Http\Requests\Company\CompanyRequest;
use App\Http\Requests\Company\CompaniesAdminFilterRequest;
use App\Http\Requests\Company\CompanyAdminUpdateReqeust;
use App\Repositories\CompanyRepository;
use App\Libs\FileHelper;
use App\Models\Company;

/**
 * Компании, партнеры.
 */
class CompanyController extends Controller
{

    use FileHelper;

    /**
     * Репозиторий компании.
     * @var CompanyRepository
     */
    protected $companies;

    /**
     * Конструктор.
     * @param CompanyRepository $company
     */
    function __construct(CompanyRepository $companies)
    {
        $this->companies = $companies;
    }

    /**
     * Список компании.
     * @return \Illuminate\Http\Response
     */
    public function companies()
    {
        return view('company.index', [
            'companies' => $this->companies->paginateCompanies(),
        ]);
    }

    /**
     * Страница компании.
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function view(Company $company)
    {
        if (!Auth::check() && $company->status == Company::UNPUBLISHED) {
            abort(404);
        }
        $this->authorize('view', $company);
        
        return view('company.view', [
            'company' => $company,
        ]);
    }

    /**
     * Страница редактировании компании.
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function updateForm(Company $company)
    {
        $this->authorize('update', $company);

        return view('company.update', [
            'company' => $company,
        ]);
    }

    protected function saveLogo(Request $request, Company $company)
    {
        if ($logo = $this->saveRequestFile($request->file('logo'), config('site.company.logo.directory', 'public/company'))) {
            if ($company->logo) {
                $company->logo->update(['file' => $logo]);
            }
            else {
                $company->logo()->create(['file' => $logo, 'field' => 'logo']);
            }
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Обновление компании.
     * @param  CompanyRequest $request
     * @param  Company        $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);
        
        $company->update($request->all());

        if ($request->hasFile('logo')) {
            if (!$this->saveLogo($request, $company)) {
                return redirect()->back()->withInput()->with('danger', trans('company.message.logo_not_save'));
            }
        }

        return redirect()->route('company', ['company' => $company])->with('success', trans('company.message.updated'));
    }

    /**
     * Получение переменны для формы.
     * @return array
     */
    protected function getFormVars()
    {
        $formVars = [];
        $formVars['statuses'] = Company::getStatuses();
        return $formVars;
    }

    /**
     * Список компании для админа.
     * @param CompaniesAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function companiesAdmin(CompaniesAdminFilterRequest $request)
    {
        $fields = ['id', 'name', 'status'];
        $companies = Helper::getRequestModel(
            Company::class,
            $request->all(),
            $fields,
            [
                'name' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ],
            [
                'order' => ['name', 'asc'],
            ]
        );

        return view('company.admin.index', [
            'companies' => $companies->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Редактирование компании.
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function updateFromAdmin(Company $company)
    {
        return view('company.admin.update', [
            'company' => $company,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Обновление компании.
     * @param  CompanyAdminUpdateReqeust $request
     * @param  Company                   $company
     * @return \Illuminate\Http\Response
     */
    public function updateAdmin(CompanyAdminUpdateReqeust $request, Company $company)
    {
        if ($request->has('status')) {
            $company->status = $request->input('status');
        }
        $company->update($request->all());
        if ($request->hasFile('logo')) {
            if (!$this->saveLogo($request, $company)) {
                return redirect()->back()->withInput()->with('danger', trans('company.message.logo_not_save'));
            }
        }
        return redirect()->route('company.update.admin', ['company' => $company])->with('success', trans('company.message.updated'));
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin(Company $company)
    {
        return view('company.admin.delete', ['company' => $company]);
    }

    /**
     * Удаление компании.
     * @param  Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.admin')->with('success', trans('company.message.deleted'));
    }
 
}

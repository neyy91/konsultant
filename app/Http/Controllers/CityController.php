<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\City\CityRequest;
use App\Http\Requests\City\CitiesAdminFilterRequest;
use App\Repositories\CityRepository;
use App\Repositories\QuestionRepository;
use App\Models\City;
use App\Models\Region;

/**
 * Контроллер для работы с городами.
 */
class CityController extends Controller
{
    /**
     * Репозиторий городов.
     * @var \App\Repositories\CityRepository
     */
    protected $city;

    /**
     * Репозиторий вопросов.
     * @var \App\Repositories\QuestionRepository
     */
    protected $questions;

    /**
     * Конструктор.
     * @param CityRepository $cities
     * @param QuestionRepository $questions
     */
    public function __construct(CityRepository $cities, QuestionRepository $questions)
    {
        $this->cities = $cities;
        $this->questions = $questions;
    }

    /**
     * Получение переменны для формы.
     * @return array
     */
    protected function getFormVars()
    {
        $formVars = [];
        $formVars['regions'] = Region::orderBy('name', 'asc')->pluck('name', 'id');
        $formVars['statuses'] = City::getStatuses();
        $formVars['sort'] = City::max('sort') + 100;
        return $formVars;
    }

    /**
     * Список городов.
     * @return \Illuminate\Http\Response
     */
    public function cities()
    {
        return view('city.index', [
            'cities' => $this->cities->getAllDefault(),
        ]);
    }

    /**
     * Переадресация на страницу города.
     * @param  intger $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectCity($id)
    {
        $city = City::findOrFail($id);
        return redirect()->route('city.view', ['city' => $city]);
    }

    /**
     * Отображение города.
     * @param  City   $city
     * @return \Illuminate\Http\Response
     */
    public function view(City $city)
    {
        if (!Auth::check() && $city->status == City::UNPUBLISHED) {
            abort(404);
        }
        $this->authorize('view', $city);
        
        return view('city.view', [
            'city' => $city,
            'questions' => $this->questions->takeByCity($city),
        ]);
    }

    /**
     * Список городов через AJAX
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function lawyersCityAjax(Request $request)
    {
        $this->validate($request, [
            'city' => 'required|string|min:2|max:20',
        ]);
        $cities = City::with('region')->where('name', 'like', '%'.$request->input('city').'%')->orderBy('name', 'asc')->take(10)->get()->all();
        
        return view('lawyer.city_ajax', [
            'cities' => $cities,
        ]);
    }

    /**
     * Список городов для админа.
     * @param CitiesAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function citiesAdmin(CitiesAdminFilterRequest $request)
    {
        $fields = ['id', 'name', 'status', 'region_id'];
        $cities = Helper::getRequestModel(
            City::class,
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
        $cities->with('region');

        return view('city.admin.index', [
            'cities' => $cities->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Отображение формы создания города.
     * @return \Illuminate\Http\Response
     */
    public function createFormAdmin(Request $request)
    {
        return view('city.admin.create', [
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Добавление городо
     * @param  CityRequest $request
     * @param  integer      $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdmin(CityRequest $request)
    {
        $regionId = $request->input('region_id');
        $regionNew = $request->input('region_new');
        if (!$regionId && $regionNew) {
            $region = Region::create(['name' => $regionNew]);
        }
        else {
            $region = Region::findOrFail($regionId);
        }
        $city = $region->cities()->create($request->except(['region_id', 'region_new', 'autoslug']));

        return redirect()->route('city.update.form.admin', ['id' => $city->id])->with('success', trans('city.message.created'));
    }

    /**
     * Обновление города.
     * @param  integer $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin(Request $request, $id)
    {
        $city = City::findOrFail($id);

        return view('city.admin.update',[
            'city' => $city,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Обновление данных города.
     * @param  CityRequest $request
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(CityRequest $request, $id)
    {
        $fields = $request->except(['region_id', 'region_new', 'autoslug']);
        // dd($fields);
        $city = City::findOrFail($id);
        if ($request->input('autoslug')) {
            $fields['slug'] = $city->generateSlug($fields);
        }
        // Обновление региона
        $regionId = $request->input('region_id');
        $regionNew = $request->input('region_new');
        if (!$regionId && $regionNew) {
            $region = Region::create(['name' => $regionNew]);
        }
        else {
            $region = Region::findOrFail($regionId);
        }
        if ($city->region_id != $region->id) {
            $city->region()->associate($region);
        }
        $city->update($fields);
        return redirect()->route('city.update.form.admin', ['id' => $city->id])->with('success', trans('city.message.updated'));
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $city = City::findOrFail($id);
        return view('city.admin.delete', ['city' => $city]);
    }

    /**
     * Удаление города.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return redirect()->route('cities.admin')->with('success', trans('city.message.deleted'));
    }

    protected function validateRegion(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'string|max:255',
            ]
        );
    }

    /**
     * Создание региона.
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createRegionAdmin(Request $request)
    {
        $this->validateRegion($request);
        $region = Region::create(['name' => $request->input('name')]);
        return redirect()->route('cities.admin')->with('success', trans('region.message.created'));
    }

    /**
     * Обновление региона.
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRegionAdmin(Request $request)
    {
        $region = Region::findOrFail($request->input('id'));
        $this->validateRegion($request);

        $region->update($request->all());
        return redirect()->route('cities.admin')->with('success', trans('region.message.updated'));
    }

}

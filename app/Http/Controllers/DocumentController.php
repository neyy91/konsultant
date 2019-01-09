<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Helper;
use App\Libs\FileHelper;
use App\Libs\GetOrCreateUserForService;
use App\Http\Requests;
use App\Http\Requests\Document\DocumentRequest;
use App\Http\Requests\Document\DocumentUpdateRequest;
use App\Http\Requests\Document\DocumentsAdminFilterRequest;
use App\Repositories\DocumentRepository;
use App\Repositories\CityRepository;
use App\Repositories\DocumentTypeRepository;
use App\Events\Document\DocumentCreate;
use App\Models\Document;
use App\Models\City;
use App\Models\DocumentType;
use App\Models\Theme;
use App\Models\User;
use App\Models\File;


/**
 * Контролер документов.
 */
class DocumentController extends Controller
{
    use FileHelper,
        GetOrCreateUserForService;

    /**
     * Репозиторий для работы с документами.
     * @var \App\Models\DocumentRepository
     */
    protected $documents;

    /**
     * Репозиторий для работы с городами.
     * @var \App\Models\CityRepository
     */
    protected $cities;

    /**
     * Репозиторий типов документа.
     * @var \App\Models\DocumentTypeRepository
     */
    protected $types;

    /**
     * Конструктор.
     * @param DocumentRepository $documents
     * @param CityRepository $cities
     * @param DocumentTypeRepository $types
     */
    public function __construct(DocumentRepository $documents, CityRepository $cities, DocumentTypeRepository $types)
    {
        $this->documents = $documents;
        $this->cities = $cities;
        $this->types = $types;
    }

    /**
     * Получение данных для формы.
     * @return array
     */
    public function getFormVars()
    {
        $formVars = [];
        $formVars['statuses'] = Document::getStatuses();
        $formVars['cities'] = $this->cities->getList();
        $formVars['document_types'] = $this->types->getList();
        return $formVars;
    }

    /**
     * Отображения списка всех документов постранично.
     * @return \Illuminate\Http\Response
     */
    public function documents()
    {
        $this->authorize('list-doc', Document::class);
        
        $documents = $this->documents->paginate();

        return view('document.index', [
            'documents' => $documents,
        ]);
    }

    /**
     * Вопросы категории права.
     * @param  DocumentType $type
     * @return \Illuminate\Http\Response
     */
    public function documentsDocumentType(DocumentType $type)
    {
        $this->authorize('list-doc', Document::class);

        $parents = [];
        if ($type->parent) {
            $parents[] = [
                'name' => $type->parent->name,
                'url' => route('documents.document_type', ['type' => $type->parent]),
            ];
         }
        return view('document.index', [
            'documents' => $this->documents->paginateByDocumentType($type),
            'filtered' => [
                'title' => $type->name,
                'description' => $type->description,
                'type' => 'document-type',
                'parents' => $parents,
            ],
            'documentType' => $type,
        ]);
    }

    /**
     * Вопросы из города.
     * @param  City $city
     * @return \Illuminate\Http\Response
     */
    public function documentsCity(City $city)
    {
        $this->authorize('list-doc', Document::class);

        return view('document.index', [
            'documents' => $this->documents->paginateByCity($city),
            'filtered' => [
                'title' => $city->name,
                'description' => $city->description,
                'type' => 'city',
                'parents' => [],
            ],
            'city' => $city,
        ]);
    }

    public function documentsUser(User $user)
    {
        $this->authorize('documents-user', Document::class);

        return view('document.index', [
            'documents' => $this->documents->paginateByUser($user),
            'filtered' => [
                'title' => $user->firstname,
                'description' => '',
                'type' => 'user',
                'parents' => [],
            ],
            'user' => $user,
        ]);
    }

    /**
     * Переадресация на страницу документа.
     * @param  intger $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToDocument($id)
    {
        $document = Document::findOrFail($id);
        return redirect()->route('document.view', ['document' => $document]);
    }

    /**
     * Отображение документа.
     * @param Request $request
     * @param  Document $document
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, Document $document)
    {
        $this->authorize('view', $document);

        if ($request->input('payed')) {
            return redirect()->route('document.view', ['document' => $document])->with('success', trans('pay.message.payment_success'));
        } elseif($request->input('failed')) {
            return redirect()->route('document', ['document' => $document])->with('error', trans('pay.message.failed'));
        }

        $document->load('answers.to', 'answers.likes', 'answers.file', 'answers.file.owner', 'answers.answers', 'answers.answers.file', 'answers.from', 'answers.from.user', 'answers.from.user.photo', 'answers.from.user.city', 'answers.from.user.documents', 'answers.from.user.documents', 'answers.from.user.calls', 'answers.from.answers', 'answers.from.qanswers', 'answers.complaints', 'answers.clarifies');
        return view('document.view', [
            'document' => $document,
        ]);
    }

    /**
     * Форма добавления документа.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createForm(Request $request)
    {
        if (!Auth::guest()) {
            $this->authorize('create', Document::class);
        }

        return view('document.create', [
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Создания вопрса.
     * @param  DocumentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(DocumentRequest $request)
    {
        $document = new Document($request->except('firstname', 'email', 'telephone'));
        // получение или создание юзера
        $result = $this->getOrCreateUserForService($request, 'create', Document::class);
        $document->user()->associate($result['user']);
        // можно сразу публиковать?
        if ($result['user']->can('publish', Document::class)) {
            $document->status = Document::STATUS_IN_WORK;
        }
        // сохранение с генерацией события
        if ($document->save()) {
            event(new DocumentCreate($document, $result['user'], $result['params']));
        }
        // загрузка файла
        if ($request->hasFile('file') && $file = $this->saveRequestFile($request->file('file'), config('site.document.file.directory', 'private/documents'))) {
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($result['user']);
            $document->file()->save($fileModel);
        }

        return redirect()->route('document.view', ['document' => $document])->with('success', trans('document.message.created'));
    }

    /**
     * Документы для админа.
     * @param  DocumentsAdminFilterRequest $request
     * @return boolean
     */
    public function documentsAdmin(DocumentsAdminFilterRequest $request)
    {
        $fields = ['id', 'title', 'city_id', 'document_type_id', 'status'];
        $documents = Helper::getRequestModel(
            Document::class,
            $request->all(),
            $fields,
            [
                'title' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
                // TODO: может и in для города, категории и статуса
            ]
        );
        $documents->with('documentType', 'city');

        return view('document.admin.index', [
            'documents' => $documents->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Обновление документа.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin($id)
    {
        $document = Document::findOrFail($id);

        return view('document.admin.update', [
            'formVars' => $this->getFormVars(),
            'document' => $document,
        ]);
    }

    /**
     * Обновление документа.
     * @param  DocumentRequest $request
     * @param  integer         $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(DocumentUpdateRequest $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->fill($request->all());
        $document->status = $request->input('status');
        $document->save();

        return redirect()->route('document.update.form.admin', ['id' => $document->id])->with('success', trans('document.message.updated'));;
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $document = Document::findOrFail($id);
        return view('document.admin.delete', ['document' => $document]);
    }

    /**
     * Удаление документа.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return redirect()->route('documents.admin')->with('warning', trans('document.message.deleted'));
    }

}

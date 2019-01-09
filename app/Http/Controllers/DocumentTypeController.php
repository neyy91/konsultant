<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\Document\DocumentTypesAdminFilterRequest;
use App\Http\Requests\Document\DocumentTypeRequest;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\DocumentRepository;
use App\Models\DocumentType;


class DocumentTypeController extends Controller
{
    /**
     * Репозиторий типов документа.
     * @var DocumentType
     */
    protected $types;

    /**
     * Репозиторий документов.
     * @var DocumentRepository
     */
    protected $documents;

    /**
     * Конструктор.
     * @param DocumentType $types
     * @param DocumentRepository $documents
     */
    public function __construct(DocumentTypeRepository $types, DocumentRepository $documents)
    {
        $this->types = $types;
        $this->documents = $documents;
    }

    /**
     * Получение переменных для формы.
     * @return array
     */
    protected function getFormVars()
    {
        $formVars = [];
        $formVars['parent_list'] = $this->types->getList();
        $formVars['statuses'] = DocumentType::getStatuses();
        $formVars['sort'] = DocumentType::max('sort') + 100;
        return $formVars;
    }

    /**
     * Список документов.
     * @return \Illuminate\Http\Response
     */
    public function documentTypes()
    {
        $documentTypes = $this->types->parentsWithChilds(config('admin.document_type.level', 3));

        return view('document_type.index', [
            'documentTypes' => $documentTypes,
        ]);
    }

    /**
     * Переадресация на страницу документов.
     * @param  intger $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToDocumentType($id)
    {
        $documentType = DocumentType::findOrFail($id);

        return redirect()->route('document_type.view', ['documentType' => $documentType]);
    }

    /**
     * Отображение документов.
     * @param  DocumentType $type
     * @return \Illuminate\Http\Response
     */
    public function view(DocumentType $type)
    {
        if (!Auth::check() && $type->status == DocumentType::UNPUBLISHED) {
            abort(404);
        }
        $this->authorize('view', $type);
        
        return view('document_type.view', [
            'documentType' => $type,
            'documents' => $this->documents->takeByDocumentType($type),
        ]);
    }


    /**
     * Список документов.
     * @param DocumentTypesAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function documentTypesAdmin(DocumentTypesAdminFilterRequest $request)
    {
        $fields = ['id', 'name', 'status', 'parent_id'];
        $documentTypes = Helper::getRequestModel(
            DocumentType::class,
            $request->all(),
            $fields,
            [
                'name' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ]
        );
        $documentTypes->with('parent');

        return view('document_type.admin.index', [
            'documentTypes' => $documentTypes->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'request' => $request,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Форма создания документов.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createFormAdmin()
    {
        return view('document_type.admin.create', [
            'formVars' => $this->getFormVars(),
        ]);
    }


    /**
     * Создания катгории права.
     * @param  DocumentTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdmin(DocumentTypeRequest $request)
    {
        $parent = DocumentType::find($request->input('parent_id'));
        if ($parent) {
            $documentType = $parent->childs()->create($request->all());
        }
        else {
            $documentType = DocumentType::create($request->all());
        }
        return redirect()->route('document_type.update.form.admin', [
            'id' => $documentType->id
        ])->with('success', trans('document_type.message.created'))->with('updating', true);
    }


    /**
     * Отображение формы обновления документов.
     * @param  Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin(Request $request, $id)
    {
        $documentType = DocumentType::findOrFail($id);
        return view('document_type.admin.update', [
            'formVars' => $this->getFormVars(),
            'documentType' => $documentType
        ]);
    }

    /**
     * Обновление документов.
     * @param  DocumentTypeRequest $request
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(DocumentTypeRequest $request, $id)
    {
        $documentType = DocumentType::findOrFail($id);
        $fields = $request->all();
        if ($request->input('autoslug')) {
            $fields['slug'] = $documentType->generateSlug($fields);
        }
        // Обновление родителя
        $parent = DocumentType::findOrFail($request->parent_id);
        if ($parent) {
            $documentType->parent()->associate($parent);
        }
        else {
            $documentType->parent()->dissociate();
        }
        $documentType->update($fields);
        return redirect()->route('document_type.update.form.admin', ['id' => $documentType->id])->with('success', trans('document_type.message.updated'))->with('updating', true);
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $documentType = DocumentType::findOrFail($id);
        return view('document_type.admin.delete', ['documentType' => $documentType]);
    }

    /**
     * Удаление документа.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $documentType = DocumentType::findOrFail($id);
        if ($documentType->childs()->count() > 0 || $documentType->documents()->count() > 0) {
            return redirect()->route('document_type.delete.form.admin', ['id' => $documentType->id])->with('warning', trans('document_type.message.childs_exists'));
        }
        $documentType->delete();
        return redirect()->route('document_types.admin')->with('warning', trans('document_type.message.deleted'))->with('updating', true);
    }
}

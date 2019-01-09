<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\FilesAdminFilterRequest;
use App\Models\File;

class FileController extends Controller
{

    /**
     * Получение переменных для формы.
     * @return array
     */
    protected function getFormVars()
    {
        $formVars = [];
        $formVars['mime_types'] = File::getMimeTypes();
        $formVars['owner_types'] = File::getOwnerTypes();
        return $formVars;
    }

    /**
     * Загрузка файла.
     * @param  File   $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(File $file)
    {
        $this->authorize('access', $file);

        return response()->file($file->full_path);
    }

    /**
     * Список файлов.
     * @param FilesAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function filesAdmin(FilesAdminFilterRequest $request)
    {
        $fields = ['basename', 'mime_type', 'owner_type', 'owner_id'];
        $files = Helper::getRequestModel(
            File::class,
            $request->all(),
            $fields,
            [
                'basename' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ]
        );
        $files->with('owner');

        return view('file.admin.index', [
            'files' => $files->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $file = File::findOrFail($id);
        \Session::flash('warning', trans('file.message.deleted_with_file'));
        return view('file.admin.delete', ['file' => $file]);
    }

    /**
     * Удаление файла.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $file = File::findOrFail($id);

        $file->delete();
        return redirect()->route('files')->with('warning', trans('file.message.deleted'));
    }

}

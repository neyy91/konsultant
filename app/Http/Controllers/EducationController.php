<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Debugbar;
use Illuminate\Http\Request;

use App\Libs\FileHelper;
use App\Http\Requests\User\EducationAdminUpdateRequest;
use App\Models\Education;
use App\Models\File;


/**
 * Образование.
 */
class EducationController extends Controller
{

    use FileHelper;

    /**
     * Получение данных для формы.
     * @return array
     */
    public function getFormVars()
    {
        $formVars = [];
        $formVars['years'] = Education::getYearsList();
        return $formVars;
    }

    /**
     * Диплом юриста для админа.
     * @param  Education $education
     * @return \Illuminate\Http\Response
     */
    public function formAdmin(Education $education)
    {
        return view('admin.education.form', [
            'education' => $education,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Обновление данных об образовании.
     * @param  EducationAdminUpdateRequest  $request
     * @param  Lawyer                       $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(EducationAdminUpdateRequest $request, Education $education)
    {
        $user = Auth::user();
        if (!Hash::check($request->input('password'), $user->getAuthPassword())) {
            return redirect()->route('user.update.admin', ['user' => $education->lawyer->user])->with('danger', trans('user.message.password_wrong'));
        }
        
        $education->fill($request->except('password'));
        $education->checked = $request->input('checked');
        $education->save();

        if ($request->hasFile('file') && $filePath = $this->saveRequestFile($request->file('file'), config('site.user.education.file.directory', 'private/education'))) {
            if ( $education->file && $education->file->count() != 0) {
                $education->file->delete();
            }
            $file = new File(['file' => $filePath, 'field' => 'file']);
            $file->user()->associate($user);
            $education->file()->save($file);
        }


        return redirect()->route('user.update.admin', ['user' => $education->lawyer->user, 'tab' => 'education'])->with('success', trans('user.message.user_education_info_updated'));
    }
}

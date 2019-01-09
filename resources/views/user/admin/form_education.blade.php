{{-- Форма для редактирования образования юриста для админа --}}

<form action="{{ route("education.update.admin", ['education' => $education]) }}" method="POST" role="form" id="formEducation" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    @macros(input, 'country', $education, ['form' => 'education', 'required' => true, 'label' => trans('user.form.education.country')])

    @macros(input, 'city', $education, ['form' => 'education', 'required' => true, 'label' => trans('user.form.education.city')])

    @macros(input, 'university', $education, ['form' => 'education', 'required' => true, 'label' => trans('user.form.education.university')])

    @macros(input, 'faculty', $education, ['form' => 'education', 'label' => trans('user.form.education.faculty')])

    @macros(select, 'year', $education, ['form' => 'education', 'required' => true, 'items' => $formVars['education_years'], 'label' => trans('user.form.education.year'), 'label_first' => trans('user.form.education.year_first')])

    @macros(select, 'checked', $education, ['form' => 'education', 'required' => true, 'type' => 'checkbox', 'label' => trans('user.form.education.checked')])

    @if ($education && $education->file)
        <a href="{{ route('file', ['file' => $education->file, 'name' => $education->file->basename]) }}" target="_blank">@lang('user.lawyer_image_diploma_uploaded')</a>
    @endif
    @macros(input, 'file', null, ['form' => 'education', 'type' => 'file', 'label' => $education && $education->file ? trans('user.replace_image_diploma') : trans('user.form.education.file')])

    @macros(input, 'password', null, ['form' => 'user', 'type' => 'password', 'required' => true, 'label' => trans('user.form.your_password')])
    
    <div class="form-group form-actions">
        @include('form.actions', ['action' => 'update', 'deleteUrl' => route('education.delete.form.admin', ['education' => $education]), 'cancelUrl' => route('users.admin')])
    </div>

</form>


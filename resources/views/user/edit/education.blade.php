{{-- 
    Форма для редактирования образования юриста.
 --}}

@php
    $route = route("user.edit.education");
    $education = $user->lawyer->education;
    $readonly = $education && $education->checked ? true : false;
@endphp
@include('form.fields')
@if ($education)
    @section('admin-links')
        <li class="item"><a href="{{ route('education.form.admin', ['education' => $education, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-education" aria-hidden="true"></span> <span class="text">{{ trans('user.action.education_edit') }}</span></a></li>
        @parent
    @stop
@endif

<form action="{{ $route }}" method="POST" role="form" id="formEditEducation" class="form form-vertical form-label-block form-edit-education ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.formSuccess">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="educationCountry" class="control-label control-label-country">{{ trans('user.form.education.country') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'country', $education, ['form' => 'education', 'group' => false, 'label' => false, 'required' => true, 'readonly' => $readonly])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="educationCity" class="control-label control-label-city">{{ trans('user.form.education.city') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'city', $education, ['form' => 'education', 'group' => false, 'label' => false, 'required' => true, 'readonly' => $readonly])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="educationUniversity" class="control-label control-label-university">{{ trans('user.form.education.university') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'university', $education, ['form' => 'education', 'group' => false, 'label' => false, 'required' => true, 'readonly' => $readonly])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="educationFaculty" class="control-label control-label-faculty">{{ trans('user.form.education.faculty') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'faculty', $education, ['form' => 'education', 'group' => false, 'label' => false, 'readonly' => $readonly])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="educationYear" class="control-label control-label-year">{{ trans('user.form.education.year') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(select, 'year', $education, ['form' => 'education', 'group' => false, 'label' => false, 'required' => true, 'items' => $formVars['years'], 'label_first' => trans('user.form.education.year_first'), 'readonly' => $readonly])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            @if ($education && $education->checked)
                <div class="clearfix col-xs-12">
                    <div class="alert alert-success">
                        <strong>{{ trans('user.form.education.checked_description') }}</strong>
                        @lang('user.form.education.checked_change_education', ['link' => route('feedback', ['theme' => 'change_education'])])
                    </div>
                </div>
            @else
                <div class="clearfix col-xs-12 col-sm-8">
                    <button type="submit" class="pull-right btn btn-success btn-submit"><span class="glyphicon @if ($education) glyphicon-refresh @else glyphicon-plus-sign @endif icon" aria-hidden="true"></span> <span class="text">{{ trans($education ? 'form.action.update_info' : 'user.add_education') }}</span></button>
                </div>
            @endif
        </div>
    </div>
</form>

{{-- Форма для подтверждения образования --}}
@if ($education && !$education->checked)
    @php
        $route = route("user.edit.education.file");
    @endphp
    <form action="{{ $route }}" method="POST" enctype="multipart/form-data" role="form" id="formEditEducationFile" class="form form-vertical form-edit-education-file form-iframe">
        {{ csrf_field() }}
        <hr>
        <h3>@lang('user.confirmation_education')</h3>
        <div class="alert alert-info @if (!$education->file || ($education->file && $education->file->count() == 0)) hidden @endif user-edit-education-file-alert">
            <strong>@lang('user.image_diploma_uploaded')</strong> @lang('user.image_diploma_uploaded_description') <a href="{{ $education->file ? route('file', ['file' => $education->file, 'name' => $education->file->basename]) : '#' }}" class="user-edit-education-file-link" target="_blank"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> @lang('user.form.education.file')</a>
        </div>
        <div class="form-group">
        <label for="educationFile" class="control-label-file"><span class="btn btn-primary disabled-set"><span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> <span class="btn-text">{{ trans($education->file && $education->file->count() >= 0 ? 'user.replace_image_diploma' : 'user.upload_image_diploma') }}</span></span></label>
            <div class="hidden">
                @macros(input, 'file', null, ['form' => 'education', 'type' => 'file', 'group' => false, 'label' => false, 'class' => 'form-submit-onchange'])
            </div>
            <div class="help-block">@lang('user.upload_image_diploma_description')</div>
        </div>
    </form>
@endif

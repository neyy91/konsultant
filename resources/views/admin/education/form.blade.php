{{-- 
    Форма для редактирования образования юриста для админа.
 --}}
@php
    $route = route("education.admin.update", ['education' => $education]);
@endphp
@extends('layouts.admin')

@section('breadcrumb')
    @parent
    <li class="active">{{ trans('user.action.education_edit') }}</li>
@stop

@include('form.fields')

@section('content')
    <h1>@lang('user.action.education_edit')</h1>
    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('user.about')</div>
                <div class="panel-body">
                    <a href="{{ route('lawyer', ['lawyer' => $education->lawyer]) }}" target="_blank">{{ $education->lawyer->user->fullname }}</a>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ $route }}" method="POST" role="form" id="formEditEducation" class="form form-vertical form-admin form-education">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @macros(select, 'checked', $education, ['form' => 'education', 'type' => 'checkbox', 'label' => trans('user.form.education.checked')])

        <div class="row">
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'country', $education, ['form' => 'education', 'disabled' => true, 'label' => trans('user.form.education.country')])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'city', $education, ['form' => 'education', 'disabled' => true, 'label' => trans('user.form.education.city')])
            </div>
            <div class="col-xs-12 col-sm-6">
                @macros(input, 'university', $education, ['form' => 'education', 'disabled' => true, 'label' => trans('user.form.education.university')])
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'faculty', $education, ['form' => 'education', 'disabled' => true, 'label' => trans('user.form.education.faculty')])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'year', $education, ['form' => 'education', 'items' => $formVars['years'], 'label_first' => trans('user.form.education.year_first'), 'disabled' => true, 'label' => trans('user.form.education.year')])
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-submit"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans('form.action.update') }}</button>
        </div>
    </form>
    <h2>@lang('user.form.education.file')</h2>
    @if ($education->file)
        <a href="{{ route('file', ['file' => $education->file, 'name' => $education->file->basename]) }}" class="user-edit-education-file-link" target="_blank"><img src="{{ route('file', ['file' => $education->file, 'name' => $education->file->basename]) }}" class="education-file education-admin-file"></a>
    @endif
@stop
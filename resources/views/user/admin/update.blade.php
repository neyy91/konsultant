{{-- Обновление пользователя --}}

@extends('layouts.admin')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('users.admin') }}">@lang('user.title')</a></li>
    <li class="active">{{ trans('user.action.edit_user', ['user' => $user->display_name]) }}</li>
@stop

@include('form.fields')

@php
    $active = request()->input('tab');
    if(!$active) {
        $active = 'user';
    }
@endphp

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-5">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="tablist @if ($active == 'user') active @endif">
                        <a href="#user" aria-controls="user" role="tab" data-toggle="tab">@lang('user.about')</a>
                    </li>

                    @if ($user->lawyer)
                        <li role="presentation" class="tablist @if ($active == 'lawyer') active @endif">
                            <a href="#lawyer" aria-controls="lawyer" role="tab" data-toggle="tab">@lang('user.lawyer')</a>
                        </li>

                        @if ($user->lawyer->education)
                            <li role="presentation" class="tablist @if ($active == 'education') active @endif">
                                <a href="#education" aria-controls="education" role="tab" data-toggle="tab">@lang('user.education')</a>
                            </li>
                        @endif
                    @endif

                </ul>
            
                <!-- Tab panes -->
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane @if ($active == 'user') active @endif" id="user">
                                @include('user.admin.form', ['user' => $user])
                            </div>

                            @if ($user->lawyer)
                                <div role="tabpanel" class="tab-pane @if ($active == 'lawyer') active @endif" id="lawyer">
                                    @include('user.admin.form_lawyer', ['lawyer' => $user->lawyer])
                                </div>

                                @if ($user->lawyer->education)
                                    <div role="tabpanel" class="tab-pane @if ($active == 'education') active @endif" id="education">
                                        @include('user.admin.form_education', ['education' => $user->lawyer->education])
                                    </div>
                                @endif

                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

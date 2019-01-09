{{--
    Список вопросов постранично
--}}
@php
    $trans = 'user.edit.' . $current;
@endphp
@extends('layouts.app')
@extends('layouts.page.' . $user->type)
@section('breadcrumb')
    @parent
    <li><a href="{{ route('user.dashboard') }}">{{ trans('user.dashboard') }}</a></li>
    <li class="active">{{ trans($trans) }}</li>
@stop
@section('content')

    
    <div role="tabpanel" class="user-edit user-edit-{{ $current }}">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <ul class="nav nav-pills nav-stacked" role="tablist">
                    @foreach ($tabs as $tab)
                        @can('edit', [App\Models\User::class, $tab])
                            <li role="presentation" @if ($current == $tab) class="active" @endif>
                                <a href="{{ $current == $tab ? '#' . $tab : route('user.edit.' . $tab . '.form') }}">{{ trans('user.edit.' . $tab) }}</a>
                            </li>
                        @endcan
                    @endforeach
                </ul>
            </div>
            <div class="col-xs-12 col-sm-9">
                <div class="tab-content tab-content-{{ $current }}">
                    <div role="tabpanel" class="tab-pane tab-pane-{{ $current }} active" id="{{ $current }}">
                        <h1 class="page-title">{{ trans($trans) }}</h1>
                        @include('user.edit.' . $current)
                    </div>
                    <div class="clearfix"></div>
                    @section('form_required')
                    <div class="form-required">
                        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
                    </div>
                    @show
                </div>
            </div>
        </div>
    </div>
@stop
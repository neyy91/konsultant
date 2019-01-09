{{-- 
    Форма удаления пользователя
--}}
@extends('layouts.admin')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('users.admin') }}">@lang('user.title')</a></li>
    <li><a href="{{ route('user.update.form.admin', ['id' => $user->id]) }}">{{ trans('user.action.edit_user', ['user' => $user->display_name]) }}</a></li>
    <li class="active">{{ trans('user.action.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['user.delete.admin', ['id' => $user->id]], 'name' => $user->display_name])
@stop

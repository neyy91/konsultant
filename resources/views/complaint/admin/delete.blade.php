{{-- 
    Форма удаления жалобы.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('complaints') }}">{{ trans('complaint.title') }}</a></li>
    <li class="active">{{ trans('complaint.form.action.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['complaint.delete.admin', ['id' => $complaint->id]], 'name' => str_limit($complaint->comment, 30)])
@stop

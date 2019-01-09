{{-- Форма удаления страницы. --}}

@extends('layouts.admin')

@section('breadcrumb')
    @parent
    <li class="active">{{ trans('page.updating_page') }}</li>
@stop

@section('content')
    @include('form.confirm.delete', ['route' => ['page.delete.admin', ['id' => $page->id]], 'name' => $page->title])
@stop

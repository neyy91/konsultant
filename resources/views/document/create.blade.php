{{-- 
    Добавление вопроса.
--}}
@extends('layouts.app')
@extends('layouts.page.one')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('document.form.legend.create') }}</li>
@stop
@section('content')
    @include('document.form', ['route' => ['create', null], 'document' => null])
@stop

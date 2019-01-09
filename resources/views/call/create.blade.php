{{-- 
    Добавление вопроса.
--}}
@extends('layouts.app')
@extends('layouts.page.one')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('call.form.legend.create') }}</li>
@stop
@section('content')
    @include('call.form', ['route' => ['create', null], 'call' => $call])
@stop

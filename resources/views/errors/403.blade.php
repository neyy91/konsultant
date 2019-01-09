{{-- Ошибка 403 --}}
@extends('layouts.app')
@extends('layouts.page.one')

@php
    $message  = trans('app.errors.403');
    $back = URL::previous();
@endphp

@section('breadcrumb')
    @parent
    <li class="active">{{ $message }}</li>
@stop

@section('content')
    <h1>{{ $message }}</h1>
    <a href="{{ $back ?: route('home')  }}" class="btn btn-primary">@lang('app.return_back')</a>
@stop


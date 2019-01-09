{{-- Ошибка 503 --}}
@extends('layouts.app')
@extends('layouts.page.one')

@php
    $back = URL::previous();
    $message = trans('app.errors.503');
@endphp

@section('breadcrumb')
    @parent
    <li class="active">{{ $message }}</li>
@stop

@section('content')
    <h1>{{ $message }}</h1>
    <a href="{{ $back ?: route('home')  }}" class="btn btn-primary">@lang('app.return_back')</a>
@stop


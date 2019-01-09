{{-- Ошибка при оплате --}}
@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li class="active">{{ $title }}</li>
@stop

@section('content')
    <div class="jumbotron pay-fail">
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a href="{{ $back  }}" class="btn btn-primary">@lang('app.return_back')</a>
    </div>
@stop


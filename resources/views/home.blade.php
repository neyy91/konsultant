{{--
    Главная страница
--}}
@extends('layouts.app')
@extends('layouts.page.one')
@section('content')
<div class="home-content">
    <h1>{{ trans('app.brand') }}</h1>

    @foreach ($blocks as $blockId => $blockHtml)
        <div class="block block-{{ $blockId }}">{!! $blockHtml !!}</div>
    @endforeach
</div>
@endsection

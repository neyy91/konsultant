{{-- 
    Список тем.
 --}}
@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li class="active">{{ trans('theme.title') }}</li>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('theme.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('theme.add_theme') }}</span></a></li>
        <li class="item"><a href="{{ route('themes.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('theme.title') }}</span></a></li>
        @parent
    @endsection
@endcan

@section('content')
    <h1>{{ trans('theme.title') }}</h1>

    <ul class="items theme-items">
    @foreach ($themes as $theme)
        <li class="item"><a href="{{ route('theme.view', ['theme' => $theme]) }}" class="link">{{ $theme->name }}</a></li>
    @endforeach
    </ul>
@stop

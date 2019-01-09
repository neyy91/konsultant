{{--
    Список городов
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li class="active">@lang('city.title')</li>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('city.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('city.add_city') }}</span></a></li>
        <li class="item"><a href="{{ route('cities.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('city.title') }}</span></a></li>
        @parent
    @endsection
@endcan

@section('content')
    <h1>@lang('city.title')</h1>

    <ul class="list list-cities">
    @forelse ($cities as $city)
        <li class="item"><h3>@if ($city->status == $city::UNPUBLISHED)<s> @endif<a href="{{ route('city.view', ['city' => $city]) }}">{{ $city->name }}@if ($city->status == $city::UNPUBLISHED)</s> @endif</a> <span class="small region">{{ $city->region->name }}</span></h3></li>
    @empty
        <li class="empty">{{ trans('app.not_found') }}</li>
    @endforelse
    </ul>
@stop
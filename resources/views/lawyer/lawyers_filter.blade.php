{{--
    Список юристов для категории или города.
--}}
@php
    function lawyers_route($category = null, $city = null)
    {
        if ($category && $city) {
            return route('lawyers.category_city', ['category' => $category, 'city' => $city]);
        }
        elseif($category) {
            return route('lawyers.category', ['category' => $category]);
        }
        elseif($city) {
            return route('lawyers.city', ['city' => $city]);
        }
        else {
            return route('lawyers');
        }
    }
@endphp
@extends('layouts.app')
@extends('layouts.page.one')
@section('admin-links')
    {{-- <li class="item"><a href="{{ route('questions.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('question.title') }}</span></a></li> --}}
    @parent
@stop
@section('breadcrumb')
    @parent
    <li><a href="{{ route('lawyers') }}">@lang('lawyer.all_lawyers')</a></li>
    @if ($breadcrumbs)
        @foreach ($breadcrumbs as $breadcrumb)
            <li><a href="{{ $breadcrumb->link }}">{{ $breadcrumb->text }}</a></li>
        @endforeach
    @else
        <li class="active">{{ strip_tags($title) }}</li>
    @endif
@stop
@section('content')
    <h1 class="h3 title-page">{{ $title }}</h1>

    <div class="panel panel-info category-city-panel">
        <div class="panel-heading">
            <h3 class="panel-title">@lang('lawyer.search_lawyers_city_specialization')</h3>
        </div>
        <div class="panel-body">
            <div class="dropdown category-dropdown lawyers-filter-category-dropdown">
                <button id="selectCategory" class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" arai-expaned="false"><span class="label-text">{{ $category ? $category->name : trans('category.select_category') }}</span> <span class="caret"></span></button>
                @if ($category)
                    <a href="{{ lawyers_route(null, $city) }}" class="btn btn-default btn-sm reset-link"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                @endif
                <ul class="dropdown-menu items" aria-labelledby="selectCategory">
                    @foreach ($categories as $cat)
                        <li class="item parent-item"><a class="item-link parent-item-link" href="{{ lawyers_route($cat, $city) }}">{{ $cat->name }}</a></li>
                        @if ($cat->childs->count() > 0)
                            @foreach ($cat->childs as $child)
                                <li class="item child-item"><a href="{{ lawyers_route($child, $city) }}" class="item-link child-item-link">{{ $child->name }}</a></li>
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="dropdown city-dropdown lawyers-filter-city-dropdown">
                <button id="selectCity" class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" arai-expaned="false"><span class="label-text">{{ $city ? $city->name : trans('city.select_city') }}</span> <span class="caret"></span></button>
                @if ($city)
                    <a href="{{ lawyers_route($category, null) }}" class="btn btn-default btn-sm reset-link"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                @endif
                <ul class="dropdown-menu items" aria-labelledby="selectCity">
                    @foreach ($cities as $cit)
                        <li class="item"><a class="item-link" href="{{ lawyers_route($category, $cit) }}">{{ $cit->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="lawyers lawyers-page">
        @include('lawyer.items', ['items' => $lawyers])
    </div>
@stop
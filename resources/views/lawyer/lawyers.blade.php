{{--
    Список юристов.
--}}
@extends('layouts.app')
@extends('layouts.page.one')

@section('admin-links')
    {{-- <li class="item"><a href="{{ route('questions.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('question.title') }}</span></a></li> --}}
    @parent
@stop

@section('breadcrumb')
    @parent
    @if ($breadcrumbs)
        @foreach ($breadcrumbs as $breadcrumb)
            <li><a href="{{ $breadcrumb->link }}">{{ $breadcrumb->text }}</a></li>
        @endforeach
    @else
        <li class="active">{{ $title }}</li>
    @endif
@stop

@section('content')
    <h1 class="title-page">{{ $title }}</h1>

    <div class="panel panel-info lawyers-categories">
        <div class="panel-heading">
            <h2 class="panel-title categories-title">@lang('lawyer.select_specialization_lawyer')</h2>
        </div>
        <div class="panel-body categories-body">
            <div class="row">
                @foreach ($categories as $num => $category)
                    <div class="col-xs-6 col-sm-4 category-col">
                        <h4 class="category-name"><a href="{{ route('lawyers.category', ['category' => $category]) }}" class="category-link category-link-parent">{{ $category->name }}</a></h4>
                        @if ($category->childs->count() > 0)
                            <ul class="category-childs">
                                @foreach ($category->childs as $child)
                                    <li class="child-item"><a href="{{ route('lawyers.category', ['category' => $child]) }}" class="category-link category-link-child">{{ $child->name }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @if ($num !==0 && ($num + 1)%2 == 0)
                        <div class="clearfix visible-xs"></div>
                    @endif
                    @if ( ($num + 1) % 3 == 0)
                        <div class="clearfix visible-sm"></div>
                    @endif
                    @if ($num == 9)
                        <div class="clearfix"></div>
                        <div class="col-xs-12"><a href="#otherCategories" class="clearfix btn btn-info btn-block show-all-spec collapsed" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="otherCategories">@lang('lawyer.show_all_specializations') <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a></div>
                        <div class="other-categories collapse" id="otherCategories">
                    @endif
                @endforeach
                            <div class="col-xs-12"><a href="#otherCategories" class="clearfix pull-right script-action" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="otherCategories">@lang('app.collapse') <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a></div>
                        </div> {{-- /#otherCategories --}}
            </div>
        </div>
    </div>

    <div class="panel panel-default lawyers-cities">
        <div class="panel-heading">
            <h2 class="panel-title cities-title">@lang('lawyer.select_lawyers_by_city')</h2>
        </div>
        <div class="panel-body cities-body">
            @foreach ($cities as $cityItem)
                @if ($city && $city->id == $cityItem->id)
                    <span class="city toggle-tooltip" title="{{ $cityItem->region->name }}">{{ $cityItem->name }}</span>
                @else
                    <a href="{{ route('lawyers.city', ['city' => $cityItem]) }}" class="city toggle-tooltip" title="{{ $cityItem->region->name }}">{{ $cityItem->name }}</a>
                @endif
            @endforeach
            <div class="row">
                <div class="col-xs-12 col-sm-3">
                    <form action="{{ route('lawyers.city.ajax') }}" method="POST" role="form">
                        <input type="text" class="form-control toggle-popover-typeahead" id="citiesFieldCity" name="city" placeholder="@lang('city.or_enter_city_name')" data-content="@lang('app.message.loaded_data')" data-placement="bottom" autocomplete="off">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="lawyers lawyers-page">
        @include('lawyer.items', ['items' => $lawyers])
    </div>
@stop
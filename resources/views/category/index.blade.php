{{-- 
    Список категории.
--}}
@macros(category_item($item))
    <a href="{{ route('category.view', ['category' => $item]) }}"@if ($item->status == $item::UNPUBLISHED) style="text-decoration: line-through;"@endif>{{ $item['name'] }}</a>
@endmacros

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li class="active">{{ trans('category.title') }}</li>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('category.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('category.add_category') }}</span></a></li>
        <li class="item"><a href="{{ route('themes.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('theme.title') }}</span></a></li>
        @parent
    @endsection
@endcan

@section('content')
    <h1>{{ trans('category.title') }}</h1>
    
    <ul class="categories parent">
    @forelse ($categories as $category)
        <li class="root">
            <h3>@macros(category_item, $category)</h3>
            @if ($childs = $category->childs)
                <ul class="category childs">
                @foreach ($childs as $child)
                    <li class="child">
                        <h4>@macros(category_item, $child)</h4>
                        @if ($childs2 = $child->childs)
                            <ul class="category childs">
                            @foreach ($childs2 as $child2)
                                <li class="child">
                                    <h5>@macros(category_item, $child2)</h5>
                                    </li>
                            @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                </ul>
            @endif
        </li>
    @empty
        <li class="empty">{{ trans('app.not_found') }}</li>
    @endforelse
    </ul>
@stop

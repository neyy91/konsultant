{{-- 
    Список категории.
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li class="active">{{ trans('document_type.title') }}</li>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('document_type.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('document_type.add_document_type') }}</span></a></li>
        <li class="item"><a href="{{ route('document_types.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('document_type.title') }}</span></a></li>
        @parent
    @endsection
@endcan

@macros(document_type_item($item))
    <a href="{{ route('document_type.view', ['type' => $item]) }}"@if ($item->status == $item::UNPUBLISHED) style="text-decoration: line-through;"@endif>{{ $item['name'] }}</a>
@endmacros

@section('content')
    <h1>@lang('document_type.title')</h1>
    
    <ul class="document-types parent">
    @forelse ($documentTypes as $documentType)
        <li class="root">
            <h3>@macros(document_type_item, $documentType)</h3>
            @if ($childs = $documentType->childs)
                <ul class="document-type childs">
                @foreach ($childs as $child)
                    <li class="child">
                        <h4>@macros(document_type_item, $child)</h4>
                        @if ($childs2 = $child->childs)
                            <ul class="document-type childs">
                            @foreach ($childs2 as $child2)
                                <li class="child">
                                    <h5>@macros(document_type_item, $child2)</h5>
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
        <li class="empty">{{ trans('document_type.not_found') }}</li>
    @endforelse
    </ul>
@stop

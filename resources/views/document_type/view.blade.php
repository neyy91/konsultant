{{--
    Отображение категории права.
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('document_types') }}">{{ trans('document_type.title') }}</a></li>
    @if ($documentType->parent)
        <li><a href="{{ route('document_type.view', ['category' => $documentType->parent]) }}">{{ $documentType->parent->name }}</a></li>
    @endif
    <li class="active">{{ $documentType->name }}</li>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('document_type.update.form.admin', ['id' => $documentType->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">{{ trans('document_type.update_document_type') }}</span></a></li>
        <li class="item"><a href="{{ route('document_type.delete.form.admin', ['id' => $documentType->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">{{ trans('document_type.delete_document_type') }}</span></a></li>
        <li class="item"><a href="{{ route('document_type.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('document_type.add_document_type') }}</span></a></li>
        <li class="item"><a href="{{ route('document_types.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('document_type.title') }}</span></a></li>
        @parent
    @endsection
@endcan

@section('content')
    <article class="document-type document-type-page">
        <h1>{{ $documentType->name }}</a></span></h1>

        <p>{{ $documentType->description }}</p>

        <div class="text">
            {{ $documentType->text }}
        </div>

    </article>
    @if (isset($documents) && count($documents) > 0)
        <section class="document-type-documents">
            <h2>@lang('document.documents.document_type') <span class="small">{{ $documentType->name }}</span></h2>
            @include('document.list', ['documents' => $documents])
            <h4 class="pull-right all all-document"><a href="{{ route('documents') }}" class="link">{{ trans('document.view_all_documents') }}</a></h4>
            <h4 class="all all-document-type"><a href="{{ route('documents.document_type', ['type' => $documentType]) }}" class="all-link all-link-document-type">@lang('document.view_all_documents_by_type', ['name' => $documentType->name])</a></h4>
        </section>
    @endif
@stop
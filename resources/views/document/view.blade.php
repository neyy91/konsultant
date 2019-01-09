{{--
    Отображение документа.
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@can('admin', \App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('document.update.form.admin', ['id' => $document->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">{{ trans('document.form.action.update') }}</span></a></li>
        <li class="item"><a href="{{ route('document.delete.form.admin', ['id' => $document->id]) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">{{ trans('document.form.action.delete') }}</span></a></li>
        <li class="item"><a href="{{ route('documents.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('document.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/view-general.js') }}"></script>
    <script>
        App.runDocument();
    </script>
@stop

@section('breadcrumb')
    @parent
    <li><a href="{{ route('documents') }}">{{ trans('document.all_documents') }}</a></li>
    @if ($document->documentType->parent)
        <li><a href="{{ route('documents.document_type', ['type' => $document->documentType->parent]) }}">{{ $document->documentType->parent->name }}</a></li>
    @endif
    <li><a href="{{ route('documents.document_type', ['type' => $document->documentType]) }}">{{ $document->documentType->name }}</a></li>
    <li class="active">{{ str_limit($document->title, config('site.breadcrumb.limit', 50)) }}</li>
@stop

@include('form.fields')

@php
    $user = Auth::user();
@endphp


@section('content')
    <article class="document-page" id="document">
        @include('document.article', ['document' => $document])
    </article>
    
    <div class="child-container child-container-clarify-answer">
        @include('clarify.form', ['type' => 'answer', 'to' => null, 'legend' => true])
    </div>

    <div class="child-container child-container-complain">
        @include('complaint.form')
    </div>

    <div class="child-container child-container-reply-answer">
        @include('answer.form_reply')
    </div>

    @if (Gate::allows('answer', [App\Models\Answer::class, $document])) 
        @include('answer.form', ['type' => 'document', 'to' => $document, 'panel' => 'primary', 'pay' => $document->cost ?: config('site.document.min_price', 800)])
    @endif

@stop
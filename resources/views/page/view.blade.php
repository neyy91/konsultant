@extends("layouts.{$page->layout}")
@extends("layouts.page.{$page->page_layout}")

@section('breadcrumb')
    @parent
    <li class="active">{{ $page->title }}</li>
@endsection

@section('title', $page->title)
@if ($page->description)
    @section('description', $page->description)
@endif

@can('admin', \App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('page.update.form.admin', ['id' => $page->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">{{ trans('page.update_page') }}</span></a></li>
        <li class="item"><a href="{{ route('page.delete.form.admin', ['id' => $page->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">{{ trans('page.delete_page') }}</span></a></li>
        <li class="item"><a href="{{ route('page.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('page.add_page') }}</span></a></li>
        <li class="item"><a href="{{ route('pages.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('page.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('content')
    <h1 class="page-title">{{ $page->title }}</h1>
    <article class="page">
        {!! $page->text !!}
    <time pubdate datetime="{{ $page->created_at->toIso8601String() }}" class="hidden"></time><time datetime="{{ $page->updated_at->toIso8601String() }}" class="hidden"></time>
    </article>
    
@endsection

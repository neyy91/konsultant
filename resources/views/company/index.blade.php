{{-- Список компании/партнеров --}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/list-others.js') }}"></script>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('companies.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('company.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('breadcrumb')
    @parent
    <li class="active">@lang('company.title')</li>
@endsection

@section('content')
    <div class="clearfix items companies">
        <h1 class="page-title">@lang('company.title')</h1>
        @forelse ($companies as $company)
            @php
                $route = route('company', ['company' => $company]);
            @endphp
            <article class="item media company">
                @if ($company->logo)
                    <a href="{{ $route }}" class="media-left imag-link"><img src="{{ $company->logo->url }}" alt="{{ $company->title }}" class="media-object image" width="100"></a>
                @endif
                <div class="media-body">
                    <h2 class="media-heading name"><a href="{{ $route }}" class="link link-name">{{ $company->name }}</a></h2>
                    @if ($company->description)
                        <div class="description description-item">
                            {{ $company->description }}
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="text-mutted empty">@lang('company.not_found')</div>
        @endforelse
        <nav class="pagination-nav" aria-label="@lang('app.pagination_label')">{{ $companies->links() }}</nav>
    </div>
@endsection
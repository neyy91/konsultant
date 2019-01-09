{{-- Страница компании --}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/view-others.js') }}"></script>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('companies.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('company.title') }}</span></a></li>
        <li class="item"><a href="{{ route('company.update.form.admin', ['company' => $company, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-edit" aria-hidden="true"></span> <span class="text">{{ trans('company.action.edit') }}</span></a></li>
        <li class="item"><a href="{{ route('company.delete.form.admin', ['company' => $company, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">{{ trans('company.action.delete') }}</span></a></li>
        @parent
    @stop
@endcan

@section('breadcrumb')
    @parent
    <li><a href="{{ route('companies') }}">@lang('company.title')</a></li>
    <li class="active">{{ $company->name }}</li>
@endsection

@php
    $cans = [
        'update' => Gate::allows('update', $company),
    ];
@endphp

@section('content')
    <aritcle class="clearfix items company">
        <h1 class="page-title">{{ $company->name }} @if ($cans['update'])
            <a href="{{ route('company.update.form', ['company' => $company]) }}" class="pull-right btn btn-primary btn-sm">@lang('form.action.edit')</a>
        @endif </h1>
        @if ($company->logo)
            <img src="{{ $company->logo->url }}" alt="{{ $company->name }}" class="img-responsive pull-left image" width="300">
        @endif
        <div class="description">{{ $company->description }}</div>

        <div class="text">
            {{ $company->text }}
        </div>

        @if ($company->lawyers->count() > 0)
            <section class="row">
                <div class="col-xs-12 col-sm-4">
                    <div class="panel panel-info panel-lawyers">
                        <div class="panel-heading">
                            <h3 class="panel-title">@lang('company.employees')</h3>
                        </div>
                        <div class="list-group">
                            @php
                                $owner = $company->owner;
                            @endphp
                            <a href="{{ route('lawyer', ['lawyer' => $owner]) }}" class="list-group-item owner-link"><strong>{{ $owner->user->display_name }} (<span class="position">@lang('company.director')</strong></span>)</a>
                            @foreach ($company->lawyers as $lawyer)
                                @continue($lawyer->companyowner)
                                <a href="{{ route('lawyer', ['lawyer' => $lawyer]) }}" class="list-group-item owner-link">{{ $lawyer->user->display_name }} (<span class="position">@lang('company.employee')</span>)</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            <div class="alert alert-info">
                @lang('company.employees_update_description', ['link' => route('user.edit.employees.form')])
            </div>
        @endif
    </article>
@endsection
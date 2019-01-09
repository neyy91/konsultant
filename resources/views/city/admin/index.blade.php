{{-- 
    Список городов для админов.
 --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('city.cities_and_regions') }}</li>
@stop
@section('content')
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#cities" aria-controls="cities" role="tab" data-toggle="tab">@lang('city.title')</a>
            </li>
            <li role="presentation">
                <a href="#regions" aria-controls="regions" role="tab" data-toggle="tab">@lang('region.title')</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="cities">
                <br>
                <h1>{{ trans('city.title') }} <a href="{{ route('city.create.form.admin') }}" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('city.add_city') }}</a></h1>
                @include('city.admin.filter')
                <table class="table table-striped table-bordered table-hover table-admin table-type-document-type">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="normal">{{ trans('city.field.name') }}</th>
                            <th>{{ trans('city.field.status') }}</th>
                            <th>{{ trans('city.field.region') }}</th>
                            <th class="action"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                            <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($cities as $city)
                        <tr>
                            <td>{{ $city->id }}</td>
                            <td class="normal"><a href="{{ route('city.update.form.admin', ['id' => $city->id]) }}">{{ $city->name }}</a></td>
                            <td>{{ $city->statusLabel }}</td>
                            <td>@if ($city->region)
                                {{ $city->region->name }}
                            @else
                                &mdash;
                            @endif</td>
                            <td class="action action-view">
                                <a href="{{ route('city.view', ['city' => $city]) }}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                            </td>
                            <td class="action action-delete">
                                <a href="{{ route('city.delete.form.admin', ['id' => $city->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $cities->links() }}
            </div>
            <div role="tabpanel" class="tab-pane" id="regions">
                <h1>{{ trans('region.title') }}</h1>
                @include('city.admin.region')
            </div>
        </div>
    </div>

@stop

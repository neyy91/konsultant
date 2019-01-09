{{-- Список пользователей для админов --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">@lang('user.title')</li>
@stop
@section('content')
    <div class="users-admin">
        <h1>@lang('user.title')</h1>

        @include('user.admin.filter')

        <table class="table table-striped table-bordered table-hover table-admin table-type-document-type">
            <thead>
                <tr>
                    <th>@lang('app.id')</th>
                    <th class="normal">@lang('user.field.fullname')</th>
                    <th>@lang('user.field.status')</th>
                    <th>@lang('user.field.type')</th>
                    <th>@lang('user.field.city')</th>
                    <th class="action action-view"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                    <th class="action action-edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></th>
                    <th class="action action-danger"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td class="normal"><a href="{{ route('user.update.form.admin', ['id' => $user->id]) }}">{{ $user->full_name }}</a></td>
                    <td>{{ $user->statusLabel }}</td>
                    <td>{{ $user->typeLabel }}</td>
                    <td>
                        @if ($user->city)
                            {{ $user->city->name }}
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td class="action action-view">
                        @if (!$user->can('provide', App\Models\User::class))
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        @else
                            <a href="{{ route('user', ['user' => $user]) }}" class="btn btn-default btn-xs" target="_block"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                        @endif
                    </td>
                    <td class="action action-update">
                        <a href="{{ route('user.update.form.admin', ['id' => $user->id]) }}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                    </td>
                    <td class="action action-delete">
                        <a href="{{ route('user.delete.form.admin', ['id' => $user->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
@stop

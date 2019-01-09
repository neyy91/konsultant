<!DOCTYPE html>
<html lang="ru">
<head>
    @can('admin', App\Models\User::class)
        @include('admin.front')
    @endcan
    @section('head')
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="@hasSection('description') @yield('description') @else @lang('app.description') @endif">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" id="csrftoken">
        <title>@hasSection('title') @yield('title') | @endif{{ trans('app.title') }}</title>
        <link rel="stylesheet" type="text/css" href="{{ asset2('assets/styles/all.css') }}">
    @show

    @php
        $user = Auth::user();
    @endphp
    @include('layouts.app.script_config')
</head>
<body id="app-layout">

    <header class="header-page">
        @include('layouts.app.header')
    </header>

    @hasSection('breadcrumb')
        <div class="container">
            <ul class="breadcrumb">
            @section('breadcrumb')
                <li><a href="{{ route('home') }}">{{ trans('app.home_title') }}</a></li>
            @show
            </ul>
        </div>
    @endif
    
    @include('layouts.app.messages')

    @yield('page')

    <footer class="footer-footer">
        @include('layouts.app.footer')
    </footer>

    @section('end')
        <script src="{{ asset2('assets/scripts/all.js') }}"></script>
    @show
    <div id="jsContainer"></div>
    @include('layouts.app.modals')
</body>
</html>

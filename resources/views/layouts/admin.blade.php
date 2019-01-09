<!DOCTYPE html>
<html lang="ru">
<head>
    @php
        $user = Auth::user();
    @endphp
    @section('head')
        @section('head')
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}" id="csrftoken">
            <title>@hasSection('title') @yield('title') | @endif{{ trans('admin.title') }}</title>
            <link href="{{ asset2('assets/styles/all.admin.css') }}" rel="stylesheet">
        <script>
            App = {};
            App.messages = {
                error: '{{ trans('app.message.error') }}'
            }
            @if (session('updating'))
                window.parent.App.pageUpdating = true;
            @endif
        </script>
    @show
</head>
<body id="admin-layout">
    <div class="container">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#admin-navbar-collapse">
                    <span class="sr-only">Навигация</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('home') }}" target="_blank" class="toggle-tooltip" data-placement="bottom" data-container="body" title="{{ trans('app.go_to_site') }}"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    @macros(menu_label($label))
                        @if (is_array($label) && $label['icon'])
                            <span class="glyphicon glyphicon-{{ $label['icon'] }}" aria-hidden="true"></span>
                        @endif
                        @if((is_string($label) && $text = $label) || (is_array($label) && $text = $label['text']))
                            {{ trans($text) }}
                        @endif
                    @endmacros
                    @macros(menu_item($menu))
                        @if (!isset($menu['route']))
                            @if (isset($menu['menu']))
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        @macros(menu_label, $menu['label'])
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach ($menu['menu'] as $submenu)
                                            @macros(menu_item, $submenu)
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                @if ($menu['label'] == '-')
                                    <li class="divider"></li>
                                @else
                                    <li class="dropdown-header">@macros(menu_label, $menu['label'])</li>
                                @endif
                            @endif
                        @else
                            @php
                                $url = route($menu['route'], isset($menu['params']) ? $menu['params'] : []);
                            @endphp
                            <li @if ($url == Request::url()) class="active" @endif><a href="{{ $url }}">@macros(menu_label, $menu['label'])</a></li>
                        @endif
                    @endmacros
                    @foreach (config('admin.menu') as $menu)
                        @macros(menu_item, $menu)
                    @endforeach
                </ul>
                 <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="{{ route('user.update.form.admin', ['user' => $user]) }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $user->display_name }}</a>
                    </li>
                 </ul>
            </div>
        </div>
    </nav>
    </div>
    @hasSection('breadcrumb')
        <div class="container">
            <ul class="breadcrumb">
            @section('breadcrumb')
                <li><a href="{{ route('admin') }}">{{ trans('admin.title') }}</a></li>
            @show
            </ul>
        </div>
    @endif
    @php
        $messages = [];
        foreach (['success', 'info', 'warning', 'danger'] as $type) {
            $text = session($type);
            if ($text) {
                $messages[$type] = $text;
            }
        }
    @endphp
    @if (!empty($messages))
        <div class="container container-messages">
            @include('common.messages')
        </div>
    @endif
    @if (isset($errors) && count($errors) > 0)
        <div class="container container-errors">
            @include('common.errors')
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="page">
        <div class="container">
            <hr>
            {{ trans('admin.copyright') }}
        </div>
    </footer>

    @section('end')
        <script>
            @if (request()->input('iframe'))
                App.isAdminIFrame = true;
            @else
                App.isAdminIFrame = false;
            @endif
        </script>

        <script src="{{ asset2('assets/scripts/all.admin.js') }}"></script>
    @show
</body>
</html>

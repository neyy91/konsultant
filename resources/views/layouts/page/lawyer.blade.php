@php
    $user = Auth::user();
    $request = request();
    $currentRoute = $request->route()->getName();
    $listItems = [
        [
            'route' => 'user.questions',
            'text' => 'question.title',
        ],
        [
            'route' => 'user.calls',
            'text' => 'call.title',
        ],
        [
            'route' => 'user.documents',
            'text' => 'document.title',
        ]
    ];
    $route = Route::current();
    $currentRoute = $route ? $route->getName() : null;
@endphp
@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
@stop
@section('page')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
               <div class="panel panel-primary user-info-left-panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('user.my_profile') }}</h3>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('lawyer', ['id' => $user->lawyer]) }}">  @include('user.profile_photo') <div class="firstname">{{ $user->lastname }}</div> <div class="lastname">{{ $user->firstname }}</div>@if ($user->city) <div class="small text-muted city">{{ $user->city->name }}</div> @endif </a>
                    </div>
                    <div class="list-group">
                        @if ($currentRoute == 'user.edit.personal.form')
                            <span class="list-group-item active">{{ trans('user.edit_profile') }}</span>
                        @else
                            <a href="{{ route('user.edit.personal.form') }}" class="list-group-item">{{ trans('user.edit_profile') }}</a>
                        @endif
                        @if ($currentRoute == 'user.edit.email_password.form')
                            <span class="list-group-item active" >{{ $user->email }}</span>
                        @else
                            <a href="{{ route('user.edit.email_password.form') }}" class="list-group-item toggle-tooltip" data-container="body" data-placement="right" title="{{ trans('user.change_email_password') }}">{{ $user->email }}</a>
                        @endif
                        @if ($currentRoute == 'user.edit.notifications.form')
                            <span class="list-group-item active">@lang('user.notification_settings')</sapn>
                        @else
                            <a href="{{ route('user.edit.notifications.form') }}" class="list-group-item">@lang('user.notification_settings')</a>
                        @endif
                    </div>
                </div> 
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('user.my_account') }}</h3>
                    </div>
                    <div class="panel-body">
                        <em class="small">@lang('user.your_actions')</em>
                    </div>
                    <div class="list-group" >
                        @if ($currentRoute == 'user.bookmarks')
                            <span class="list-group-item active">{{ trans('bookmark.my_bookmarks') }}</span>
                        @else
                            <a href="{{ route('user.bookmarks') }}" class="list-group-item">{{ trans('bookmark.my_bookmarks') }}</a>
                        @endif
                        @can('companyOwner', App\Models\Lawyer::class)
                            @if ($currentRoute == 'user.edit.employees.form')
                                <span class="list-group-item active">{{ trans('user.my_employees') }}</span>
                            @else
                                <a href="{{ route('user.edit.employees.form') }}" class="list-group-item">{{ trans('user.my_employees') }}</a>
                            @endif
                        @endcan
                        @if ($currentRoute == 'user.chats')
                            <span class="list-group-item active">@lang('chat.dialogs')</span>
                        @else
                            <a href="{{ route('user.chats') }}" class="list-group-item">@lang('chat.dialogs')</a>
                        @endif
                        <a href="{{ route('page', ['page' => 'terms']) }}" class="list-group-item" target="_blank">{{ trans('user.terms') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                @yield('content')
            </div>
        </div>
    </div>
@stop


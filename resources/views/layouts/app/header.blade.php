<nav class="navbar navbar-default navbar-static top-navbar">
    <div class="container">
        <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Навигация</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{ route('home') }}">
                {{ trans('app.brand') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-nav">
                @can('provide', \App\Models\User::class)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('app.actual_orders') <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ route('questions') }}">@lang('question.title')</a></li>
                            @if (Gate::allows('list-doc', App\Models\Document::class))
                                <li><a href="{{ route('documents') }}">@lang('document.title')</a></li>
                            @endif
                            @if (Gate::allows('list-call', App\Models\Call::class))
                                <li><a href="{{ route('calls') }}">@lang('call.title')</a></li>
                            @endif
                        </ul>
                    </li>
                    <li><a href="{{ route('lawyers') }}">@lang('user.lawyers')</a></li>
                    <li><a href="{{ route('page', ['slug' => 'faq']) }}">@lang('app.faq')</a></li>
                @else
                    @can('admin', \App\Models\User::class)
                        <li><a href="{{ route('questions') }}">@lang('question.title')</a></li>
                        <li><a href="{{ route('documents') }}">@lang('document.title')</a></li>
                        <li><a href="{{ route('calls') }}">@lang('call.title')</a></li>
                        <li><a href="{{ route('complaints.admin') }}" class="link-admin link-menu" target="iframeAdmin" data-target="#modalAdmin">@lang('complaint.title')</a></li>
                    @else
                        <li><a href="{{ route('question.create.form') }}">@lang('question.ask')</a></li>
                        <li><a href="{{ route('document.create.form') }}">@lang('document.order_document')</a></li>
                        <li><a href="{{ route('call.create.form') }}">@lang('call.request_call')</a></li>
                        <li><a href="{{ route('lawyers') }}">@lang('user.lawyers')</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('app.more') <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('questions') }}">@lang('question.title')</a></li>
                                <li><a href="{{ route('page', ['slug' => 'help']) }}">@lang('app.how_it_works')</a></li>
                                <li><a href="{{ route('page', ['slug' => 'contacts']) }}">@lang('app.contacts')</a></li>
                            </ul>
                        </li>
                    @endcan
                @endcan
            </ul>

             <ul class="nav navbar-nav navbar-right">
            
                @if (Auth::guest())
                    @if (\Route::current() && \Route::current()->getName() == 'login')
                        <li><a href="{{ route('register') }}" class="navbar-register">@lang('user.registration')</a></li>
                    @else
                        <li><a class="navbar-login" href="#login" data-toggle="modal"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> @lang('user.login')</a></li>
                    @endif
                @else
                    <li class="menu-chat-message"><a href="{{ route('user.chats') }}" class="menu-chat-message-link"><span class="text-primary glyphicon glyphicon-envelope" aria-hidden="true"></span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> {{ $user->display_name }}<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ route('user.dashboard') }}">{{ trans('user.dashboard') }}</a></li>
                            @if ($user->lawyer)
                                <li><a href="{{ route('lawyer', ['id' => $user->lawyer->id]) }}">{{ trans('user.my_profile') }}</a></li>
                            @endif
                            <li><a href="{{ route('user.edit.email_password') }}" class="toggle-tooltip" data-container="body" data-placement="left" title="{{ trans('user.change_email_password') }}">{{ $user->email }}</a></li>
                            <li><a href="{{ route('user.edit') }}">{{ trans('user.edit_profile') }}</a></li>
                            @if ($user->lawyer)
                                <li><a href="{{ route('user.bookmarks') }}">{{ trans('bookmark.bookmarks') }}</a></li>
                            @endif
                            <li class="divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" role="form" id="formLogout" class="form-logout">{{ csrf_field() }}<button type="submit" class="btn btn-link">{{ trans('user.logout') }}</button></form>
                            </li>
                        </ul>
                    </li>
                @endif
            
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle=dropdown role=button aria-expanded=false><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('companies') }}">{{ trans('company.title') }}</a></li>
                        <li><a href="{{ route('categories') }}">{{ trans('category.title') }}</a></li>
                        <li><a href="{{ route('document_types') }}">{{ trans('document_type.title') }}</a></li>
                        <li><a href="{{ route('themes') }}">{{ trans('theme.title') }}</a></li>
                        <li><a href="{{ route('cities') }}">{{ trans('city.title') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
{{-- Чат с пользователем --}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    <script>
        App.config.chat.show = false;
    </script>
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
    <script>
        App.Chat.pageInit(App.config.chat);
    </script>
@stop

@section('breadcrumb')
    @parent
    <li><a href="{{ route('user.dashboard') }}">@lang('user.dashboard')</a></li>
    <li><a href="{{ route('user.chats') }}">@lang('chat.title')</a></li>
    <li class="active">{{ $user->display_name }}@if ($user->city), @lang('city.prefix') {{ $user->city->name }} @endif</li>
@stop

@include('user.photo')
@include('form.fields')

@section('content')
    <div class="chat chat-page" id="chatPage">
        <h1 class="title title-page"></h1>
        {{-- <div class="row">
            <div class="col-xs-12 col-sm-3">
                <div class="dialogs" id="dialogs">
                    @forelse ($dialogs as $dialog)
                        @php
                            $route = route('user.chat.view', ['user' => $dialog->to]);
                        @endphp
                        <a href="{{ $route }}" class="dialog @if ($dialog->to->id == $user->id) dialog-active @endif" data-id="{{ $dialog->id }}" data-user="{{ $dialog->to->id }}">
                            <span class="media dialog-media">
                                @macros(user_photo, ['user' => $dialog->to, 'size' => 'small', 'attributes' => ['class' => 'pull-left image']])
                                <span class="media-body">
                                    <span class="media-heading name">
                                        {{ $dialog->to->display_name }}
                                        <br>
                                        <span class="small city">@lang('city.prefix') {{ $dialog->to->city->name }}</span>
                                    </span>
                                </span>
                            </span>
                        </a>
                    @empty
                        <div class="empty">@lang('chat.not_dialogs')</div>
                    @endforelse
                </div>
            </div>
            <div class="col-xs-12 col-sm-9"> --}}
                <div class="bg-info about">
                    {{-- <form action="{{ route('user.chat.delete', ['user' => $user]) }}" class="pull-right chat-delete-form" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="text-danger chat-delete-action" title="@lang('app.close')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                    </form> --}}
                    <div class="media">
                        @if ($user->lawyer)
                            <a class="pull-left" href="{{ route('lawyer', ['lawyer' => $user->lawyer]) }}" class="link-image" target="_blank">
                                @macros(user_photo, ['user' => $user, 'size' => 'xsmall'])
                            </a>
                        @else
                            <div class="pull-left">
                                @macros(user_photo, ['user' => $user, 'size' => 'xsmall'])
                            </div>
                        @endif
                        <div class="media-body">
                            <h3 class="media-heading">
                                @if ($user->lawyer)
                                    <a href="{{ route('lawyer', ['lawyer' => $user->lawyer]) }}" class="link-name" target="_blank">{{ $user->display_name }}</a>
                                @else
                                    <span class="link-text">{{ $user->display_name }}</span>
                                @endif
                                @if ($user->city)
                                    <span class="small city">@lang('city.prefix') {{ $user->city->name }}</span>
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>
                
                <div class="chat-messages" id="chatMessages" data-user-id="{{ $user->id }}">
                    <div class="row chat-messages-items">
                        @include('user.chat.messages', ['chatList' => $chatList, 'me' => $me])
                    </div>
                </div>
                <div class="form">
                    @php
                        $route = route('user.chat.message.send', ['user' => $user]);
                    @endphp
                    <form class="chat-form form-message-send ajax" role="form" method="POST" action="{{ $route }}" data-on="submit" data-ajax-method="POST" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.messageSend">
                        <input type="hidden" name="last" value="{{ $chatList->count() > 0 ? $chatList->last()->id : '' }}" class="chat-form-last">
                        @macros(textarea, 'message', null, ['form' => 'chat', 'id' => false, 'required' => true, 'rows' => 3, 'label' => false, 'class' => 'chat-message-text-enter', 'placeholder' => trans('chat.form.message')])
                        <div class="form-group">
                            <button type="submit" class="pull-right btn btn-primary">
                                <span class="glyphicon glyphicon-send" aria-hidden="true"></span> &nbsp; 
                                <span class="text">@lang('form.action.send')</span>
                            </button>
                        </div>
                    </form>
                </div>
            {{-- </div>
        </div> --}}
    </div>
@endsection
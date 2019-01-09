{{-- Чаты с пользователями --}}

@extends('layouts.app')
@extends('layouts.page.' . $user->type)

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
@stop

@section('breadcrumb')
    @parent
    <li><a href="{{ route('user.dashboard') }}">{{ trans('user.dashboard') }}</a></li>
    <li class="active">@lang('chat.title')</li>
@stop

@include('user.photo')

@section('content')
    <div class="user-chats user-chats-page">
        <h1 class="title-page">@lang('chat.dialogs')</h1>

        @php
            $users = [];
        @endphp
        <div class="items">
            @forelse ($chats as $chat)
                @php
                    $with = $chat->from_id == $user->id ? $chat->to : $chat->from;
                    if (isset($users[$with->id])) {
                        continue;
                    }
                    $users[$with->id] = 1;
                @endphp
                <article class="arcticle user-chat">
                    <div class="row">
                        <div class="col-xs-3 col-sm-1 image-col">
                            @macros(user_photo, ['user' => $with, 'size' => 'small'])
                        </div>
                        <div class="col-xs-9 col-sm-5 name-col">
                            <h3 class="name">
                            @if ($with->can('provide-user', [App\Models\User::class, $with]))
                                <a href="{{ route('lawyer', ['lawyer' => $with->lawyer]) }}" class="name-link">{{ $with->display_name }}</a>
                            @else
                                {{ $with->display_name }}
                            @endif
                            </h3>
                            @if ($with->city)
                                <div class="city">@lang('city.prefix') {{ $with->city->name }}</div>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-2 message-col">
                            <a href="#" role="button" class="btn btn-success lawyer-chat-link ajax" data-on="click" data-ajax-url="{{ route('user.chat', ['user' => $with]) }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.startSuccess">@lang('lawyer.contact_lawyer')</a>
                        </div>
                    </div>
                </article>
                <hr>
                {{ $chats->links() }}
            @empty
                @lang('chat.not_found')
            @endforelse
        </div>
                
    </div>
@endsection
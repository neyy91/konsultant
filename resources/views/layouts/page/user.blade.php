{{--
    Шаблон страниц клиента.
--}}
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
        ],
        [
            'route' => 'user.chats',
            'text' => 'chat.dialogs',
        ]
    ];
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
                         <h2 class="panel-title">{{ trans('user.my_profile') }}</h2>
                     </div>
                     <div class="panel-body">
                         <a href="{{ route('user.edit') }}" class="profile-link"> @include('user.profile_photo') <div class="short-name">{{ $user->firstname }}</div>@if ($user->city) <div class="small text-muted city">{{ $user->city->name }}</div> @endif <div class="small edit-text">{{ trans('user.edit_profile') }}</div></a>
                     </div>
                     <div class="list-group">
                         <a href="{{ route('user.edit.notifications') }}" class="list-group-item">{{ trans('user.email_notifications') }}</a>
                     </div>
                 </div> 
                 <div class="panel panel-info">
                     <div class="panel-heading">
                         <h2 class="panel-title">{{ trans('user.services_site') }}</h2>
                     </div>
                     <div class="panel-body text-muted">
                         <em>{{ trans('user.services_site_description') }}</em>
                     </div>
                     <div class="list-group">
                        @foreach ($listItems as $item)
                            @if ($currentRoute == $item['route'])
                                <li class="list-group-item active">{{ trans($item['text']) }}</li>
                            @else
                                 <a href="{{ route($item['route']) }}" class="list-group-item"><b class="text-info">{{ trans($item['text']) }}</b></a>
                            @endif
                        @endforeach
                     </div>
                 </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                @yield('content')
            </div>
        </div>
    </div>
@stop
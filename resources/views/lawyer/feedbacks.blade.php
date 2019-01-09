{{-- Все отзывы --}}
@extends('layouts.app')
@extends('layouts.page.one')

@php
    $user = $lawyer->user;
@endphp

@section('breadcrumb')
    @parent
    <li><a href="{{ route('lawyers') }}">{{ trans('lawyer.lawyers') }}</a></li>
    <li><a href="{{ route('lawyer', ['lawyer' => $lawyer]) }}">{{ $user->display_name }}</a></li>
    <li class="active">@lang('like.title')</li>
@stop

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
@stop

@section('content')
    @include('lawyer.info', ['lawyer' => $lawyer, 'liked' => $likedInfo, 'link' => route('lawyer', $lawyer)])
    <div class="profile-lawyer profile-lawyer-liked-page">
        <h1 class="lawyer-liked-title"> {{ trans_choice('like.total_likes', $likedInfo['count']['all'], ['total' => $likedInfo['count']['all']]) }}</h1>
        <div class="lawyer-liked">
            @include('like.items', ['likes' => $liked])
        </div>
        <div class="lawyer-liked-pagination">
            {{ $liked->links() }}
        </div>
    </div>
@stop

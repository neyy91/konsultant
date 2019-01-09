{{-- Форма обратной связи --}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li class="active">@lang('feedback.title')</li>
@stop

@php
    $route = route('feedback.send');
@endphp

@include('form.fields')

@section('content')
    <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
            <form action="{{ $route }}" role="form" method="POST" class="form-feedback ajax" data-on="submit" data-ajax-method="POST" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.formSuccess">

                @php
                    if (old('theme')) {
                        $theme = old('theme');
                    }
                @endphp
                @macros(select, 'theme', null, ['form' => 'feedback', 'required' => true, 'items' => trans('feedback.themes'), 'value' => $theme])

                @php
                    $contact = old('contact');
                    if (!$contact && $user) {
                        $contact = $user->email;
                    }
                @endphp
                @macros(input, 'contact', null, ['form' => 'feedback', 'required' => true, 'value' => $contact])

                @macros(textarea, 'text', null, ['form' => 'feedback', 'required' => true, 'rows' => 7, 'value' => old('message')])

                <div class="form-group">
                    <div class="pull-left required-help">
                        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
                    </div>
                    <button type="submit" class="pull-right btn btn-primary btn-lg">
                        <span class="glyphicon glyphicon-send" aria-hidden="true"></span> &nbsp; 
                        <span class="text">@lang('form.action.send')</span>
                    </button>
                </div>

                
            </form>
        </div>
    </div>
@stop
{{-- 
    Форма для ответа
    @var $question
--}}
@unlessmacros(textarea)
    @include('form.fields')
@endif

@php
    $route = route('expertise.create', ['id' => $question->id]);
@endphp

<form action="{{ $route }}" method="POST" role="form" id="expertiseForm" class="form expertise-form ajax" data-on="submit" data-ajax-url="{{ $route }}" data-ajax-method="POST" data-ajax-data="App.serializeToObject" data-ajax-context="this" data-ajax-success="App.expertiseCreate" data-ajax-before-send="App.disableForm" data-ajax-complete="App.enableForm">
    {{ csrf_field() }}
    <legend>@lang('expertise.form_title')</legend>

    @macros(textarea, 'message', null, ['form' => 'expertise', 'rows' => 5, 'required' => true])

    <div class="form-group">
        <button type="submit" class="btn btn-info pull-right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {{ trans('expertise.action.add_message') }}</button>
    </div>

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>
</form>


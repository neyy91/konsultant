{{-- Форма рейтинга --}}
@include('form.fields')
@php
    $route = route('answer.rating', ['answer' => $answer]);
@endphp
<form class="clearfix form-like ajax" data-on="submit" data-ajax-url="{{ $route }}" data-ajax-method="PUT" data-ajax-context="this" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-success="App.likedUpdate" data-ajax-error="App.messageOnError">
    @macros(textarea, 'text', null, ['form' => 'like', 'rows' => 3, 'label' => false, 'placeholder' => trans('like.add_comments_to_review')])
    <div class="form-group">
        <button type="submit" class="pull-right btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('like.form.action.add_comment') }}</button>
    </div>
</form>
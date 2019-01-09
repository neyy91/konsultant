{{-- 
    Действия для формы админки.
    @var string $action
    @var string $deleteUrl
 --}}
@php
 if (Request::url() != URL::previous()) {
    $cancelUrl = URL::previous();
 }
 // или $cancelUrl из параметра include
@endphp

@if ($action == 'create')
    <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans("form.action.add") }}</button>
@elseif ($action == 'update')
    @if ($deleteUrl)
        <a href="{{ $deleteUrl }}" class="btn btn-danger btn-sm pull-right toggle-tooltip" title="{{ trans('form.action.delete') }}" data-container="body" data-placement="left"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
    @endif
    <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans("form.action.update") }}</button> 
@endif
<a href="{{ $cancelUrl }}" class="btn btn-link">{{ trans('form.action.cancel') }}</a>
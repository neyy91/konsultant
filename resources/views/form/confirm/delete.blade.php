{{-- 
    Форма подтвержения операции удаления
--}}
<form action="{{ route($route[0], $route[1]) }}" method="POST" role="form">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <legend>@if (isset($legend))
        {{ $legend }}
    @else
        @lang('form.legend.delete_confirm', ['name' => $name])
    @endif</legend>
    @if (isset($text))
        <div class="text">{{ $text }}</div>
    @endif
    <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> &nbsp; {{ trans("form.action.yes_delete") }}</button>
    <a href="{{ isset($cancelUrl) && $cancelUrl ? $cancelUrl : URL::previous() }}" class="btn btn-link">{{ trans('form.action.cancel') }}</a>
</form>

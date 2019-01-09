{{-- 
    Форма для очистки корзины
 --}}
<form action="{{ route($route) }}" method="POST" role="form">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button type="submit" class="btn btn-danger btn-{{ $btn or 'sm' }}"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> @if(isset($text) && $text === true) {{ trans('trash.clean') }} @elseif(isset($text) && $text) {{ $text }} @endif </button>
</form>
{{-- 
    Выпадающее меню с админ ссылками.
 --}}
@macros(admin_dropdown($params))
@php
    $params['dropdownClass'] = $params['dropdownClass'] ? $params['dropdownClass'] : 'dropdown-' . $type;
    $params['btnClass'] = $params['btnClass'] ? $params['btnClass'] : 'btn-default';
@endphp
<div class="dropdown {{ $params['dropdownClass'] }}">
    <button type="button" class="btn {{ $params['btnClass'] }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
    <ul class="clearfix items dropdown-menu">
        <li class="item"><a href="{{ route($params['type'] . '.update.form.admin', ['id' => $params['model']->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link link-admin" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">{{ trans('form.action.update') }}</span></a></li>
        <li class="item"><a href="{{ route($params['type'] . '.delete.form.admin', ['id' => $params['model']->id, 'iframe' => 'y']) }}" class="link link-admin" target="iframeAdmin" data-target="#modalAdmin"><span class="text-danger glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text-danger text">{{ trans('form.action.delete') }}</span></a></li>
        @if (isset($params['items']))
            @foreach ($params['items'] as $item)
                <li class="item"><a href="{{ route($item['route'], ['iframe' => 'y'] + $item['params']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin">@if ($item['icon'])<span class="icon glyphicon glyphicon-{{ $item['icon'] }}" aria-hidden="true"></span> @endif<span class="text">{{ $item['text'] }}</span></a></li>
            @endforeach
        @endif
    </ul>
</div>
@endmacros
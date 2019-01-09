{{-- 
    Список уточнений
 --}}

@macros(clarify_list($params))
     @foreach ($params['clarifies'] as $key => $clarify)
        @if ($key != 0)
            <hr>
        @endif
        <div class="clarify clarify-{{ $params['type'] }}" id="{{ $params['type'] }}Clarify{{ $clarify->id }}">
            <div class="text">{{ $clarify->text }}</div>
            <div class="bottom">
                <time pubdate datetime="{{ $clarify->created_at->toIso8601String() }}" class="date date-pub">{{ $clarify->created_at->format(config('site.date.long', 'd.m.Y, H:i')) }}</time>@if ($clarify->file),
                    <a href="{{ route('file', ['file' => $clarify->file, 'name' => $clarify->file->basename]) }}" class="file-link file-type-"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> {{ trans('clarify.uploaded_file') }}</a>
                @endif
            </div>
        </div>
    @endforeach
@endmacros

{{-- 'type', 'count', 'id' --}}
@macros(clarify_title_collapse($params))
    @php
        $ucfType = ucfirst($params['type']);
    @endphp
    @if ($params['count'] > 0)
        <a href="#clarifies{{ $ucfType }}{{ $params['id'] }}" class="small script-action toggle-collapse toggle-clarifies collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="clarifies{{ $ucfType }}{{ $params['id'] }}"><span class="show-text toggle-tooltip" data-container="body" data-placement="top" title="{{ trans('clarify.show_clarifies') }}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> {{ trans('app.show') }}</span><span class="hide-text toggle-tooltip" data-container="body" data-placement="top" title="{{ trans('clarify.hide_clarifies') }}"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> {{ trans('app.hide') }}</span></a>
    @endif
@endmacros


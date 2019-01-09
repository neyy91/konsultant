@macros(user_photo($params))
    @php
        $sizes = config('site.user.photo.sizes');
        $params = array_merge([
            'size' => 'small',
            'attributes' => [],
        ], $params);
        $alt = isset($params['attributes']['alt']) ? $params['attributes']['alt'] : $params['user']->display_name;
        unset($params['attributes']['alt']);
        $attributesHtml = '';
        foreach ($params['attributes'] as $key => $attribute) {
            $attributesHtml .= ' ' . $key . '="' . $attribute . '"';
        }
    @endphp
    @if ($params['user']->photo)
        <img src="{{ $params['user']->photo->url }}" alt="{{ $alt }}" width="{{ $sizes[$params['size']][0] }}"{!! $attributesHtml !!}>
    @else
        <img src="{{ default_user_photo($params['user']) }}" alt="{{ $alt }}" width="{{ $sizes[$params['size']][0] }}"{!! $attributesHtml !!}>
    @endif
@endmacros
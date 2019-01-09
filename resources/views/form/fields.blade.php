{{--
    Fields macros define
--}}
@php
    function field_get_options($name, $options)
    {
        $defaultOptions = [
            'form' => 'form',
            'group' => true,
            'id' => null,
            'required' => false,
            'type' => 'text',
            'class' => '',
            'class_label' => '',
            'rows' => 10,
            'label' => true,
            'label_first' => true,
            'placeholder' => null,
            'value' => null,
            'default_value' => '',
            'idplus' => null,
            'helps' => false,
            'disabled' => false,
            'multiple' => false,
            'key' => '',
            'readonly' => false,
            'wrap' => true,
            'attributes' => [],
         ];
         $options = array_merge($defaultOptions, $options);
         $vars = [
            'nameDash' => str_replace('_', '-', $name),
         ];
         if ($options['class']) {
            $options['class'] = ' ' . $options['class'];
         }
         if ($options['id'] !== false && !$options['id']) {
            $options['id'] = camel_case($options['form'] . ($options['idplus'] ? '_' . $options['idplus'] . '_' : '_') . $name  . ($options['key'] ? '_' . $options['key'] . '_' : ''));
         }
         if ($options['form']) {
            if ($options['label'] === true) {
                $options['label'] = trans("{$options['form']}.form.$name");
            }
            if ($options['label_first'] === true) {
                $options['label_first'] = trans("{$options['form']}.form.{$name}_first");
            }
         }
        return [$vars, $options];
    }
    function field_get_value($name, $model, $options) {
        $value = $options['value'];
        if (!$value) {
            $value = old($name);
            if (!$value) {
                $value = $model && isset($model->$name) ? $model->$name : $options['default_value'];
            }
        }
        return $value;
    }

@endphp

@macros(label_help($help))
    @if ($help)
        <span class="glyphicon glyphicon-info-sign toggle-tooltip text-muted" aria-hidden="true" data-container="body" data-placement="right" title="{{ $help }}"></span>
    @endif
@endmacros

@macros(input($name, $model = null, $options = []) )
    @php
        list($vars, $options) = field_get_options($name, $options);
        $value = field_get_value($name, $model, $options);
        $values = is_array($value) ? $value : [$value];
        $required  = $options['required'] && !$options['multiple'] || $options['required'] && $options['multiple'] && $num == 0;
    @endphp
    @if ($options['group'])
        <div class="form-group form-group-{{ $vars['nameDash'] }}">
    @endif
    @if ($options['label'])
        <label for="{{ $options['id'] }}" class="control-label control-label-{{ $vars['nameDash'] }} {{ $options['class_label'] }}">{{ $options['label'] }}@if ($options['required']) <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> @endif @if ($options['helps']) @macros(label_help, $helps) @endif </label>
    @endif
    @foreach ($values as $num => $value)
        <input name="{{ $name . ($options['key'] ? '[' . $options['key'] . ']' : '') . ($options['multiple'] ? '[]' : '') }}" type="{{ $options['type'] }}" class="form-control form-control-{{ $options['type'] }} {{ $options['multiple'] ? 'form-control-multiple' : '' }} form-field-{{ $vars['nameDash'] }}{{ $options['class'] }}" id="{{ $options['id'] . ($options['multiple'] && $num > 0 ? $num : '' ) }}" value="{{ $value }}"@if ($required) required @endif @if ($options['placeholder']) placeholder="{{ is_string($options['placeholder']) ? $options['placeholder'] : trans("{$options['form']}.form.$name") }}" @endif @if ($options['disabled']) disabled @endif @if ($options['readonly']) readonly @endif @if (!empty($options['attributes'])) @foreach ($options['attributes'] as $key => $value) {{ $key }} = "{{ $value }}"@endforeach @endif >
    @endforeach
    @if ($options['group'])
        </div>
    @endif
@endmacros

@macros(select($name, $model = null, $options = []) )
    @php
        $options['type'] = isset($options['type']) ? $options['type'] : 'select';
        $options['first_optgroup'] = isset($options['first_optgroup']) ? $options['first_optgroup'] : false;
        list($vars, $options) = field_get_options($name, $options);
        $select_value = field_get_value($name, $model, $options);
    @endphp
    @if ($options['group'])
        <div class="form-group form-group-{{ $vars['nameDash'] }}">
    @endif
    @if ($options['type'] == 'select')
        @if ($options['label'])
            <label for="{{ $options['id'] }}" class="control-label control-label-{{ $vars['nameDash'] }} {{ $options['class_label'] }}">{{ $options['label'] }}@if ($options['required']) <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> @endif @if ($options['helps']) @macros(label_help,$options['helps']) @endif </label>
        @endif
        <select name="{{ $name . ($options['multiple'] ? '[]' : '') }}" id="{{ $options['id'] }}" class="form-control form-control-select @if ($options['multiple']) form-control-multiple @endif form-field-{{ $name }}{{ $options['class'] }}"@if ($options['required']) required @endif @if ($options['readonly']) readonly @endif @if ($options['disabled']) disabled @endif @if ($options['multiple']) multiple @endif>
        @if ($options['label_first'])
            @if ($options['first_optgroup'])
                <optgroup label="{{ $options['label_first'] }}" class="form-option-first">
            @else
                <option value="" class="form-option-first" @if (!$select_value) selected @endif>{{ $options['label_first'] }}</option>
            @endif
        @endif
        @foreach ($options['items'] as $value => $text)
            @continue(!empty($options['except']) && in_array($value, $options['except']))
            <option value="{{ $value }}"@if ($select_value !== '' && $select_value == $value) selected @endif>{{ $text }}</option>
        @endforeach
        @if ($options['first_optgroup'])
            </optgroup>
        @endif
        </select>
    @elseif($options['type'] == 'checkbox' || $options['type'] == 'radio')
        @if ($options['wrap'])
            <div class="{{ $options['type'] }} {{ $options['type'] }}-{{ $vars['nameDash'] }}">
        @endif
        @php
            $multiple = true;
            if (!isset($options['items']) || empty($options['items'])) {
                $options['items'] = [1 => $options['label']];
                $multiple = false;
            }
        @endphp
        @foreach ($options['items'] as $value => $text)
            @if ($options['label'])
                <label>
            @endif
            <input type="{{ $options['type'] }}" value="{{ $value }}" name="{{ $name . ($options['key'] ? '[' . $options['key'] . ']' : '') . ($options['multiple'] ? '[]' : '') }}" id="{{ $options['id'] . ($multiple ? ucfirst($value) : '') }}"@if ($select_value == $value) checked @endif class="form-control-{{ $options['type'] }} form-field-{{ $vars['nameDash'] }}{{ $options['class'] }}">
                @if ($options['label'])
                    <span class="label-text {{ $options['type'] }}-label-text label-text-{{ $vars['nameDash'] }} {{ $options['class_label'] }}">{{ $text }}</span> @if ($options['helps']) @macros(label_help, $options['helps'][$value]) @endif
                @endif
            @if ($options['label'])
                </label>
            @endif
        @endforeach
        @if ($options['wrap'])
            </div>
        @endif
    @endif
    @if ($options['group'])
        </div>
    @endif
@endmacros


@macros(textarea($name, $model = null, $options) )
    @php
        $options['type'] = isset($options['type']) ? $options['type'] : 'select';
        list($vars, $options) = field_get_options($name, $options);
        $value = field_get_value($name, $model,$options);
    @endphp
    @if ($options['group'])
        <div class="form-group form-group-{{ $vars['nameDash'] }}">
    @endif
    @if ($options['label'])
        <label for="{{ $options['id'] }}" class="control-label control-label-{{ $vars['nameDash'] }} {{ $options['class_label'] }}">{{ $options['label'] }}@if ($options['required']) <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> @endif</label>
    @endif
    <textarea name="{{ $name }}" id="{{ $options['id'] }}" class="form-control form-control-textarea form-field-{{ $name }}{{ $options['class'] }}" rows="{{ $options['rows'] }}"@if ($options['required']) required @endif @if ($options['placeholder']) placeholder="{{ $options['placeholder'] }}" @endif @if ($options['readonly']) readonly @endif>{{ $value }}</textarea>
    @if ($options['group'])
        </div>
    @endif
@endmacros
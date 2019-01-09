{{-- 
    Форма добавления/обновления города.
--}}
@include('form.fields')
<form action="{{ route("city.{$route[0]}.admin", $route[1]) }}" method="POST" role="form">
    {{ csrf_field() }}
    @if ($route[0] == 'update')
        {{ method_field('PUT') }}
    @endif
    <legend>{{ trans("city.form.legend.{$route[0]}") }}</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'name', $city, ['form' => 'city', 'required' => true, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'status', $city, ['form' => 'city', 'items' => $formVars['statuses'], 'group' => false, 'value' => $city ? '' : App\Models\City::PUBLISHED])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-2">
                <label> </label>
                @macros(select, 'autoslug', $city, ['form' => 'city', 'type' => 'checkbox', 'group' => false, 'value' => $city ? '' : 1])
            </div>
            <div class="col-xs-10">
                @macros(input, 'slug', $city, ['form' => 'city', 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-5">
                <label for="cityRegionId" class="control-label control-label-region-id">{{ trans("city.form.region_id") }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
                @macros(select, 'region_id', $city, ['form' => 'city', 'items' => $formVars['regions'], 'group' => false, 'label' => false])
                <div class="help-block">{{ trans('city.select_region_or_empty_for_new') }}</div>
            </div>
            <div class="col-xs-11 col-sm-5">
                <label> &nbsp; </label>
                @macros(input, 'region_new', $city, ['form' => 'city', 'group' => false, 'placeholder' => trans('city.form.region_new'), 'label' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(input, 'sort', $city, ['form' => 'city', 'type' => 'number', 'group' => false, 'value' => !$city && isset($formVars['sort']) ? $formVars['sort'] : ''])
            </div>
        </div>
    </div>

    @macros(textarea, 'description', $city, ['form' => 'city', 'rows' => 3])

    @macros(textarea, 'text', $city, ['form' => 'city', 'rows' => 10])

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => $route[0], 'deleteUrl' => $city ? route('city.delete.form.admin', ['id' => $city->id]) : null, 'cancelUrl' => route('cities.admin')])
    </div>

</form>

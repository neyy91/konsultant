{{-- 
    Форма добавления вопроса.
--}}
@include('form.fields')

<form action="{{ route("question.create") }}" enctype="multipart/form-data" method="POST" role="form" id="formQuestion" class="form-question">
    {{ csrf_field() }}

    <legend>{{ trans('question.form.legend.create') }}</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="questionFrom" class="control-label control-label-from">{{ trans('question.form.from') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'from', $question, ['form' => 'question', 'items' => $formVars['froms'], 'group' => false, 'type' => 'radio', 'default_value' => App\Models\Question::FROM_DEFAULT])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="questionCategoryLawId" class="control-label control-label-category-law-id">{{ trans('question.form.category_law_id') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'category_law_id', $question, ['form' => 'question', 'items' => $formVars['categories'], 'required' => true, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="questionTitle" class="control-label control-label-title">{{ trans('question.form.title_create') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'title', $question, ['form' => 'question', 'required' => true, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3"><label for="questionDescription" class="control-label control-label-description">{{ trans('question.form.description') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label></div>
            <div class="col-xs-12 col-sm-9">
                @macros(textarea, 'description', $question, ['form' => 'question', 'rows' => 20, 'required' => true, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="questionFile">{{ trans('question.form.file') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'file', $question, ['form' => 'question', 'type' => 'file', 'label' => false, 'group' => false])
                <div class="help-block">{{ trans('form.max_file_size', [ 'size' => config('site.question.file.max_size', 500)]) }}</div>
            </div>
        </div>
    </div>

    @php
        $user = \Auth::guest() ? null : Auth::user();
    @endphp
    @if (!$user)
        @include('common.create_reg', ['col' => 3])
    @endif

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="userTelephone" class="control-label control-label-telephone">{{ trans('user.form.telephone') }}</label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'telephone', $user ? $user : null, ['form' => 'user', 'label' => false, 'group' => false, 'placeholder' => trans('user.form.telephone_example'), 'attributes' => ['pattern' => '^\d+$']])
                <small class="help-block">@lang('user.form.telephone_format')</small>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="questionCityId" class="control-label control-label-city-id">{{ trans('question.form.city_id') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            @php
                $city = $question && $question->city_id ? $question->city_id : ($user && $user->city_id ? $user->city_id : old('city_id'));
            @endphp
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'city_id', $question, ['form' => 'question', 'items' => $formVars['cities'], 'required' => true, 'label' => false, 'group' => false, 'value' => $city])
            </div>
        </div>
    </div>

    @include('pay.question', ['question' => $question])

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
            <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> {{ trans('question.form.action.ask') }}</button>
    </div>

</form>
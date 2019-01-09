{{-- Форма поиска --}}
<form action="{{ route('questions') }}" method="GET" class="question-search-form">
    <div class="row">
        <div class="col-xs-12 col-sm-8">
            @macros(input, 'q', null, ['form' => 'search', 'label' => false, 'group' => false, 'placeholder' => trans('question.enter_search_text'), 'value' => request('q') ? request('q') : old('q')])
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="input-group">
                @macros(select, 'category', null, ['form' => 'search', 'items' => $formVars['categories'], 'label' => false, 'group' => false, 'label_first' => trans('theme.in_all_themes'), 'value' => isset($categoryLaw) ? $categoryLaw->id : old('category')])
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary" name="search" value="y">@lang('form.action.search')</button>
                </span>
            </div>
        </div>
    </div>
</form>
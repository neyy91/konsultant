<div class="modal fade" id="answerSetIs">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-answer-is-question ajax" data-on="submit" data-ajax-url="" data-ajax-method="PUT" data-ajax-context="this" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.beforeSetIsForQuestion" data-ajax-success="App.setIsForQuestion" data-ajax-error="App.messageOnErrorForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">@lang('answer.is_help.question')</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <label for="answerRate" class="control-label question-answer-rate-label">@lang('answer.form.rate_first')</label>
                        </div>
                        <div class="col-xs-2">
                            @macros(select, 'rate', null, ['form' => 'answer', 'items' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5], 'label' => false, 'label_first' => false, 'default_value' => 5])
                        </div>
                    </div>
                    @macros(textarea, 'comment', null, ['form' => 'answer', 'rows' => 3, 'label' => false, 'placeholder' => trans('like.add_comments_to_review')])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary answer-is-action">@lang('answer.is_help.question')</button>
                </div>
            </form>
        </div>
    </div>
</div>


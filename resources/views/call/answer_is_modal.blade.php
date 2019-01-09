<div class="modal fade" id="answerSetIs">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-answer-is-call ajax" data-on="submit" data-ajax-url="" data-ajax-method="PUT" data-ajax-context="this" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.beforeSetIsForCall" data-ajax-success="App.setIsForCall" data-ajax-error="App.messageOnErrorForm">
                <input type="hidden" name="is" value="1">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">@lang('answer.is_help.call')</h4>
                </div>
                <div class="modal-body">
                    <div class="answer-is-call-confirm">@lang('answer.is_confirm.call')</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary answer-is-action">@lang('answer.is_help.call')</button>
                </div>
            </form>
        </div>
    </div>
</div>

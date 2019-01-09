<div class="modal fade" id="answerSetIs">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-answer-is-document ajax" data-on="submit" data-ajax-url="" data-ajax-method="PUT" data-ajax-context="this" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.beforeSetIsForDocument" data-ajax-success="App.setIsForDocument" data-ajax-error="App.messageOnErrorForm">
                <input type="hidden" name="is" value="1">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">@lang('answer.is_help.document')</h4>
                </div>
                <div class="modal-body">
                    <div class="answer-is-document-confirm">@lang('answer.is_confirm.document')</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary answer-is-action">@lang('answer.is_help.document')</button>
                </div>
            </form>
        </div>
    </div>
</div>

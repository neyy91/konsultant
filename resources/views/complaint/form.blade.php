{{-- 
    Форма для жалобы на всё.
--}}
<form action="" method="POST" role="form" id="formComplainAgainst" class="complain-form ajax" data-on="submit" data-ajax-method="POST" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-context="this" data-ajax-url="" data-ajax-before-send="App.disableForm" data-ajax-complete="App.enableForm" data-ajax-success="App.complaintOf" data-ajax-error="App.messageOnError">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-xs-12 col-sm-8">
            <legend>{{ trans('complaint.report_abuse') }}</legend>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                @macros(select, 'type', null, ['form' => 'complaint', 'group' => false, 'type' => 'radio', 'items' => App\Models\Complaint::getTypes(), 'helps' => App\Models\Complaint::getTypeDescriptions(), 'value' => App\Models\Complaint::TYPE_OTHER])
            </div>

            <div class="col-xs-12 col-sm-4">
                @macros(textarea, 'comment', null, ['form' => 'complaint', 'rows' => 7])
                <div class="clearfix">
                    <button type="submit" class="btn btn-sm btn-danger form-submit form-submit-complaint pull-right"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> <span class="form-submit-text">{{ trans('complaint.send_complaint') }}</span></button>
                </div>
            </div>
        </div>
    </div>
</form>

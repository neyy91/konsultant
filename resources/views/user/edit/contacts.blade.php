{{-- 
    Форма редактирования контактов юриста.
--}}
@php
    $route = route("user.edit.contacts");
    $lawyer = $user->lawyer;
@endphp
@include('form.fields')
@section('form_required') @stop

@macros(phone_addons())
    <span class="input-group-addon"><a href="#" class="text-danger remove-tags" data-remove="" data-parent=".input-group:first" data-container="body" title="{{ trans('form.action.remove') }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></span>
@endmacros

<form action="{{ $route }}" method="POST" role="form" id="formEditContacts" class="form form-vertical form-label-block form-edit-contacts ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.formSuccess">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    {{-- <legend>{{ trans('user.form.legend.contacts') }}</legend> --}}
    {{-- {{ dd($lawyer->toArray()) }} --}}
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="contactsContactphones" class="control-label control-label-contactphones">{{ trans('user.form.contactphones') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
            {{-- {{ dd($lawyer->contactphones) }} --}}
                @foreach ($lawyer->contactphones as $num => $contactphone)
                    @php
                        $addons = $num != 0 && $contactphone;
                    @endphp
                    <div class="contactphone-wrap @if ($addons) input-group input-group-multiple @endif">
                        @macros(input, 'contactphones', null, ['form' => 'contacts', 'group' => false, 'label' => false, 'multiple' => true, 'value' => $contactphone])
                    @if ($addons)
                            @macros(phone_addons)
                        @endif
                    </div>
                @endforeach
                <div id="contactphonesMore"></div>
                <a class="script-action add-more" href="#contactphonesMore" data-element="#contactphonesHtml">{{ trans('app.add_more') }}</a>
                <script type="text/template" id="contactphonesHtml">
                    <div class="input-group input-group-multiple">
                        @macros(input, 'contactphones', null, ['form' => 'contacts', 'group' => false, 'label' => false, 'placeholder' => trans('user.enter_telephone'), 'multiple' => true])
                        @macros(phone_addons)
                    </div>
                </script>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="contactsContactemail" class="control-label control-label-contactemail">{{ trans('user.form.contactemail') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'contactemail', $lawyer, ['form' => 'contacts', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="contactsFax" class="control-label control-label-fax">{{ trans('user.form.fax') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'fax', $lawyer, ['form' => 'contacts', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="contactsSite" class="control-label control-label-site">{{ trans('user.form.site') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'site', $lawyer, ['form' => 'contacts', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="contactsSkype" class="control-label control-label-skype">{{ trans('user.form.skype') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'skype', $lawyer, ['form' => 'contacts', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="clearfix col-xs-12 col-sm-8">
                <button type="submit" class="pull-right btn btn-success"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans('form.action.save_data') }}</button>
            </div>
        </div>
    </div>
</form>

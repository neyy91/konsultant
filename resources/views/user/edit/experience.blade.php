{{-- 
    Форма для редактирования опыта.
 --}}

@php
    $route = route("user.edit.experience");
    $lawyer = $user->lawyer;
    $specializations = $lawyer->specializations()->pluck('id')->all();
@endphp
@include('form.fields')
@section('form_required') @stop

<form action="{{ $route }}" method="POST" role="form" id="formEditChat" class="form form-vertical form-label-block form-edit-chat ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.formSuccess">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12">
                <label class="control-label control-label-specialization">{{ trans('user.select_specialization') }}</label>
            </div>
            @foreach ($formVars['specializations'] as $spec)
                <div class="col-xs-12 col-sm-6">
                    @macros(select, 'specialization', null, ['value' => in_array($spec->id, $specializations) ? $spec->id : null, 'items' => [$spec->id => $spec->name], 'form' => 'user', 'type' => 'checkbox', 'multiple' => true, 'group' => false, 'class_label' => 'text-primary'])
                    @if ($childs = $spec->childs)
                        <div class="row">
                            <div class="col-xs-11 col-xs-offset-1">
                                @foreach ($childs as $child)
                                    @macros(select, 'specialization', null, ['value' => in_array($child->id, $specializations) ? $child->id : null, 'items' => [$child->id => $child->name], 'form' => 'user', 'type' => 'checkbox', 'multiple' => true, 'group' => false])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <h3>@lang('user.current_job')</h3>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="userCompany" class="control-label control-label-companyname">{{ trans('user.form.companyname') }}</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                @macros(textarea, 'companyname', $lawyer, ['form' => 'user', 'rows' => 2, 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="userPosition" class="control-label control-label-position">{{ trans('user.form.position') }}</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                @macros(input, 'position', $lawyer, ['form' => 'user', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="userExperience" class="control-label control-label-experience">{{ trans('user.form.experience') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'experience', $lawyer, ['form' => 'user', 'group' => false, 'items' => $formVars['experiences'], 'label_first' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="clearfix col-xs-12 col-sm-10">
                <button type="submit" class="pull-right btn btn-success btn-submit"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans('form.action.save_data') }}</button>
            </div>
        </div>
    </div>
</form>

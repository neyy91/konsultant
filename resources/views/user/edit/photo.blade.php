{{-- 
    Форма сохраниния фото.
--}}
@php
    $route = route("user.edit.photo");
@endphp
@include('form.fields')
@section('form_required') @stop

<form action="{{ $route }}" method="POST" enctype="multipart/form-data" role="form" id="formEditPhoto" class="form form-vertical form-edit-photo form-iframe">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    {{-- <legend>{{ trans('user.form.legend.photo') }}</legend> --}}

    <div class="form-group">
        <div class="col-xs-12 user-photos">
            @include('user.edit.photo_list')
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-12">
            <label for="photoPhoto" class="control-label control-label-photo">{{ trans('form.action.upload_new_photo') }}</label>
        </div>
        <div class="col-xs-12 col-sm-4">
            @macros(input, 'photo', null, ['form' => 'photo', 'type' => 'file', 'group' => false, 'label' => false])
        </div>
        <div class="col-xs-12 col-sm-4">
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> {{ trans('form.action.upload') }}</button>
        </div>
    </div>
</form>

{{-- 
    Форма сохраниния фото.
--}}
@php
    $route = route("user.edit.honors");
    $honors = $user->lawyer->honors;
@endphp
@include('form.fields')
@section('form_required') @stop
<div class="row user-edit-honor-items">
    @for ($number = 0; $number < 4; $number++)
        @php
            $honor = isset($honors[$number]) ? $honors[$number] : null;
        @endphp
        <div class="col-xs-12 col-sm-3 honor-col @if (!$honor) user-edit-honor-empty @endif">
            <div class="honor-item" @if ($honor) style="background-image: url({{ $honor->url }});" @endif >
                    <a href="#" class="btn btn-xs btn-danger pull-right honor-delete ajax" data-on="click" data-ajax-url="{{ $honor ? route('user.edit.honor.delete', ['honor' => $honor]) : '' }}" data-ajax-method="DELETE" data-ajax-data-type="json" data-ajax-context='{ "parents": ".honor-col:first"}' data-ajax-success="App.honorDelete" data-ajax-error="App.messageOnError"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                    <span class="glyphicon glyphicon-certificate honor-icon"></span>
            </div>
        </div>
    @endfor
</div>
<form action="{{ $route }}" method="POST" enctype="multipart/form-data" role="form" id="formEditHonors" class="form form-vertical form-edit-honors form-iframe @if ($honors->count() >= config('site.user.honors.count', 4)) hidden @endif">
    {{ csrf_field() }}
    <input type="hidden" name="check" value="1">
    <hr>
    <div class="form-group">
        <label for="userHonor" class="control-label-honor"><span class="btn btn-primary disabled-set"><span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> {{ trans('user.upload_new_honor') }}</span></label>
        <div class="hidden">
            @macros(input, 'honor', null, ['form' => 'user', 'type' => 'file', 'group' => false, 'label' => false, 'class' => 'form-submit-onchange'])
        </div>
        <div class="help-block">@lang('user.honor_upload_description')</div>
        
        {{-- <div class="col-xs-12 col-sm-4">
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> {{ trans('user.upload_new_honor') }}</button>
        </div> --}}
    </div>
</form>


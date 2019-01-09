{{-- 
    Создания города.
--}}
@if (!function_exists('field_get_options'))
    @include('form.fields')
@endif

<div class="row">
    <div class="col-xs-12 col-sm-8">
        <form action="{{ route('region.update') }}" method="POST" role="form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <legend>{{ trans('region.form.legend.update') }}</legend>

            <div class="row">
                <div class="col-xs-12 col-sm-5">
                    @macros(select, 'id', null, ['form' => 'region', 'items' => $formVars['regions'], 'required' => true])
                </div>
                <div class="col-xs-6 col-sm-5">
                    @macros(input, 'name', null, ['form' => 'region', 'required' => true])
                </div>
                <div class="col-xs-6 col-sm-2">
                    <label> &nbsp; </label>
                    <div class="clearfix form-actions">
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-xs-12 col-sm-4">
        <form action="{{ route('region.create') }}" method="POST" role="form">
            {{ csrf_field() }}
            <legend>{{ trans('region.form.legend.create') }}</legend>

            <div class="row">
                <div class="col-xs-10">
                    @macros(input, 'name', null, ['form' => 'region', 'required' => true])
                </div>
                <div class="col-xs-2">
                    <label> &nbsp; </label>
                    <div class="clearfix form-actions">
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

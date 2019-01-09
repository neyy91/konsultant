@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <h1 class="panel-heading">@lang('user.authorization')</h1>
                <div class="panel-body">
                    @include('user.login_form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{--
    Шаблон с одной колонкой.
--}}

@section('page')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                @yield('content')
            </div>
        </div>
    </div>
@stop
@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    @parent
    {{-- <script src="/assets/scripts/pushstream.js"></script> --}}
    <script type="text/javascript">
        if(!pushstream) {
            console.log('pushstream');
            var pushstream = new PushStream({
                host: window.location.hostname,
                port: 8000,
                // modes: "longpolling",
                modes: "websocket|eventsource|longpolling",
            });
            pushstream.onmessage = function(data) {
                console.log('onmessage');
                console.log(data);
            };
            pushstream.onstatuschange = function(state) {
                if(state == PushStream.OPEN) {
                    console.log('pushstream open');
                }
                console.log(state);
            }
            try {
                pushstream.addChannel('some');
                pushstream.connect();
            }
            catch(e) {
                alert(e)
            };
        }
    </script>
@endsection

@section('content')
<div class="home-content">
    <h1>Test stream page</h1>
</div>
@endsection

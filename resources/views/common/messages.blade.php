@foreach ($messages as $type => $message)
    <div class="alert alert-{{ $type }}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        @if (is_array($message))
            <ul>
                @foreach ($message as $m)
                    <li>{{ $m }}</li>
                @endforeach
            </ul>
        @else
            {{ $message }}
        @endif
    </div>
@endforeach

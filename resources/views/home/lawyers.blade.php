@unlessmacros(user_photo)
    @include('user.photo')
@endif

<div class="home-lawyers">
    <h2 class="title">@lang('lawyer.consultations_from_lawyers_advocate', ['count' => $count])</h2>
    @foreach ($lawyers as $lawyer)
        <a href="{{ route('lawyer', ['lawyer' => $lawyer]) }}" class="lawyer">
            @macros(user_photo, ['user' => $lawyer->user, 'size' => 'large', 'attributes' => ['class' => 'img-responsive line image']])
            <span class="line name">{{ $lawyer->user->display_name }}</span>
            @if ($lawyer->user->city)
                <span class="line city">{{ $lawyer->user->city->name }}</span>
            @endif
        </a>
    @endforeach
    <div class="text-center">
        <a href="{{ route('lawyers') }}" class="btn btn-success btn-lg">@lang('lawyer.all_lawyers_project')</a>
    </div>
</div>
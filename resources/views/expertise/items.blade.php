{{-- 
    Список сообщений экспертов.
--}}
@forelse ($expertises as $key => $expertise)
    @if ($key != 0)
        <hr>
    @endif
    <div class="expertise" id="expertise{{ $expertise->id }}">
        @php
            $lawyer = $expertise->lawyer;
            $user = $lawyer->user;
            $lawyerUrl = route('lawyer', ['lawyer']);
        @endphp
        <div class="clearfix about">
            <div class="media expertise-media expertise-about">
                @can('admin', App\Models\User::class)
                    <a href="{{ route('expertise.delete.form.admin', ['id' => $expertise->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link-admin" data-target="#modalAdmin"><span class="pull-right text-danger icon glyphicon glyphicon-trash" aria-hidden="true"></span></a></li>
                @endcan
                <a class="pull-left link-image" href="{{ $lawyerUrl }}">
                    @php
                        $sizes = config('site.user.photo.sizes');
                        $src = $user->photo ? $user->photo->url : default_user_photo($user);
                    @endphp
                    <img src="{{ $src }}" alt="{{ $user->display_name }}" width="{{ $sizes['xsmall'][0] }}" class="media-object image">
                </a>
                <div class="media-body">
                    <h3 class="media-heading lawyer lawyer-expertise"><a href="{{ route('lawyer', ['lawyer' => $lawyer]) }}" class="link lawyer-expertise-link">{{ $user->display_name }}</a> <a href="{{ route('question',['question' => $expertise->question]) }}#expertise{{ $expertise->id }}" class="small expertise-anchor toggle-tooltip" data-container="body" title="{{ trans('expertise.link_to') }}"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></a> <span class="small lawyer-smallinfo">{{ ($lawyer->status ? $lawyer->statusLabel : '') }}@if ($user->city){{ ($lawyer->status ? ', ' : '') . $user->city->name }} @endif</span></h3>
                    
                </div>
            </div>
        </div>
        <div class="message expertise-message">{{ $expertise->message }}</div>
    </div>
@empty
    <div class="empty">{{ trans('expertise.not_found') }}</div>
    <hr>
@endforelse

{{-- 
    Письмо со ссылко для изменения email.
 --}}

<p>{{ trans('user.mail.email_change.title') }}</p>

<p>{{ trans('user.mail.email_change.new_email_text', ['email' => $email]) }}</p>

<p>{{ trans('user.mail.email_change.url_text') }} <a href="{{ $url }}">{{ $url }}</a></p>
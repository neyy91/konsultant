{{-- 
    Письмо со ссылко для изменения email.
 --}}

{{ trans('user.mail.email_change.title') }}

{{ trans('user.mail.email_change.new_email_text', ['email' => $email]) }}

{{ trans('user.mail.email_change.url_text') }} {{ $url }}
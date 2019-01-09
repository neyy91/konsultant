<p>Поздравляю! Вы зарегистрировались на сайте <a href="{{ route('home') }}">konsultant.ru</a></p>
<p>Email: {{ $user->email }}</p>
@if ($params['password'])
    <p>Мы сгенерировали для Вас пароль: {{ $params['password'] }}</p>
    <p>Сохраните пароль, чтобы <a href="{{ route('login') }}">войти на сайт</a></p>
@endif
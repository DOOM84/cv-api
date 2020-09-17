@component('mail::message')
    # Здравствуйте!

    Ваш пароль: {!! $pass !!}

    С уважением,
    {{ config('app.name') }}
@endcomponent

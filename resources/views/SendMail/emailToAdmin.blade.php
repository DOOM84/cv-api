@component('mail::message')
# Здравствуйте!

С сайта отправлено сообщение для Вас!<br>
E-mail написавшего:
@component('mail::button', ['url' => "mailto:$email"])
    {{$email}}
@endcomponent

Имя: {!! $name !!} <br>
Сообщение: {!! $message !!} <br>

С уважением,<br>
{{env('APP_NAME')}}
@endcomponent

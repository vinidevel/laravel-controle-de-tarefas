@component('mail::message')
# Oi, Otária

Clique no botão e veja o que você precisa fazer pra ganhar dinheiro

@component('mail::button', ['url' => 'https://www.cursoemvideo.com/'])
Botão
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent

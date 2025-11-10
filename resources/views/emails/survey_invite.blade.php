@component('mail::message')
# Invitación a Encuesta: {{ $survey->title }}

Has sido invitado a participar en la encuesta "{{ $survey->title }}".

@component('mail::button', ['url' => url(route('survey.fill', $unique_link, false))])
Responder Encuesta
@endcomponent

Si el botón no funciona, copia y pega este enlace en tu navegador:

{{ url(route('survey.fill', $unique_link, false)) }}

Gracias,
{{ config('app.name') }}
@endcomponent

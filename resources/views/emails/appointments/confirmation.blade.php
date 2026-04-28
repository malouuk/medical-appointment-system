<x-mail::message>
# {{ __('messages.mail_subject') }}

{{ __('messages.mail_greeting') }} **{{ $appointment->user->name }}**,

{{ __('messages.mail_confirmed') }}

**{{ __('messages.mail_details') }} :**
- **{{ __('messages.service') }} :** {{ $appointment->service->name }}
- **{{ __('messages.date_time') }} :** {{ $appointment->appointment_date->format('d/m/Y H:i') }}
- **{{ __('messages.duration') }} :** {{ $appointment->service->duration }} min
- **{{ __('messages.price') }} :** {{ number_format($appointment->service->price, 2) }} €

@if($appointment->notes)
**{{ __('messages.notes') }} :**
{{ $appointment->notes }}
@endif

<x-mail::button :url="route('appointments.show', $appointment->id)">
{{ __('messages.appointment_details') }}
</x-mail::button>

{{ __('messages.security_note') }},<br>
{{ config('app.name') }}
</x-mail::message>

@component('mail::message')
# Change your password

click on the link below to change your password if this is not from you please ignore this message

@component('mail::button', ['url' => 'http://localhost:8000/reset-password?token='.$token])
Password Reset
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# New Account in {{ config('app.name') }}

Hello {{ $name }},

You have been created an account in {{ config('app.name') }} by an administrator. You can now follow this link in order to activate your account and **set your password**.


@component('mail::button', ['url' => $link])
    Set a password
@endcomponent

**Note:** The previous link expires in {{ config('phyto.email_verification_time') }} days. If it has already been expired, use [this form]({{ route('verification.resend')  }}) to send another email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# New assignments

Hello {{ $user }},

You have been assigned {{ $assignmentCount }} new images to be tagged in project “{{ $project }}”.


@component('mail::button', ['url' => $link])
    View assignments
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

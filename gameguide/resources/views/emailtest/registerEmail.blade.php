@component('mail::message')
# Introduction

The body of my mesage sent by laravel with mailtrap.

@component('mail::button', ['url' => 'http://127.0.0.1:8000/admin/webinar'])
visit site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

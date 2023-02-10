<x-mail::message>
The following certificates will expire in the next 3 months, time to get re-certified

@component('mail::table')
| Name | Issued By | Expires On |
| ---- | --------- | ---------- |
@foreach($certificates as $certificate)
| {{ $certificate->name }} | {{ $certificate->issued_by }} | @if($certificate->expires_on) {{ $certificate->expires_on->format('d/m/Y') }} @else No Date @endif |
@endforeach
@endcomponent

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

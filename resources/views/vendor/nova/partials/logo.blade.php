@if(Auth::user())
    <img src="{{ asset('integra-logo-oficial-blanco.svg') }}" alt="Fundación integra">
@else
<img src="{{ asset('integra-logo-oficial-gris.svg') }}" alt="Fundación integra">
@endif
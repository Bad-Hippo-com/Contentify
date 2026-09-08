@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Aktion bestätigen</h1>
        <p>Diese Aktion verändert Daten. Bitte bestätige sie ausdrücklich.</p>
        <form method="post" action="{{ $action }}">
            @csrf
            <button type="submit">Bestätigen</button>
        </form>
        <p><a href="{{ url('/') }}">Abbrechen und zur Website</a></p>
    </div>
@stop

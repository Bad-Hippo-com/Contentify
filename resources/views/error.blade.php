@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Anfrage nicht möglich ({{ $status ?? 500 }})</h1>

        <hr>

        <p>Die Anfrage konnte nicht ausgeführt werden.</p>
        <p>Prüfe bitte deine Anmeldung und versuche es gegebenenfalls erneut.</p>

        <hr>
        
        <button onclick="window.location='{!! route('home') !!}'">Website</button>
    </div>
@stop

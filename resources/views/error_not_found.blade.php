@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Seite nicht gefunden</h1>

        <hr>

        <p>Die angeforderte Seite ist nicht verfügbar.</p>
        <p>Vielleicht hilft die {!! link_to('search', 'Suche') !!} weiter.</p>

        <hr>

        <button onclick="window.location='{!! route('home') !!}'">Website</button>
    </div>
@stop

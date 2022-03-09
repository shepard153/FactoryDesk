@extends('ticket/ticket_template')
 
@section('title', 'RUGDesk')
 
@section('navbar')
    @parent
 
@endsection
 
@section('content')
        <div class="alert alert-success text-center"><h4>Pomyślnie dodano zgłoszenie o numerze <strong>{{ $ticketID }}</strong>.</h4> <h2><a href="{{ url('/') }}" onClick="window.close();">Zamknij okno</a></h2></div>';
@endsection
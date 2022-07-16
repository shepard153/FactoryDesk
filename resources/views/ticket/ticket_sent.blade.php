@extends('ticket/ticket_template')

@section('title', 'RUGDesk')

@section('navbar')
    @parent

@endsection

@section('content')
  <div class="alert alert-success text-center mt-2">
    <h4>{!! $ticketSentMessage !!}</h4><br/>
    <a href="{{ url('/') }}" onClick="window.close();">
      <button type="button" class="btn btn-danger btn-lg">{{ __('raise_ticket_form.close_form') }}</button>
    </a>
  </div>
@endsection

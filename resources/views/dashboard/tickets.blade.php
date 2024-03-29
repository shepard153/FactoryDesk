@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <a href="{{ url ('tickets/awaiting') }}" class="btn btn-primary">{{ __('dashboard_tickets.filter_awaiting') }}</a>
  <a href="{{ url ('tickets/new') }}" class="btn btn-success">{{ __('dashboard_tickets.filter_new') }}</a>
  <a href="{{ url ('tickets/taken') }}" class="btn btn-warning">{{ __('dashboard_tickets.filter_in_progress') }}</a>
  <a href="{{ url ('tickets/closed') }}" class="btn btn-danger">{{ __('dashboard_tickets.filter_closed') }}</a>
  <a href="{{ url ('tickets/active') }}" class="btn btn-secondary">{{ __('dashboard_tickets.filter_active') }}</a>
  <div class="col rounded shadow" style="background: white; margin-top: 1vw;">
    @if ($tickets->count() > 0)
      <table class="table table-hover">
        <div id="paging">
          <p class="lead" style="padding: 0.7vw 0px 0px 1vw;">
            {{ __('dashboard_tickets.pagination_displaying', ['start' => $tickets->firstItem(), 'end' => $tickets->lastItem(), 'total' => $tickets->total()]) }}
          </p>
        </div>
        <nav aria-label="paging" style="padding-left: 1vw; margin-bottom: -1.5vw">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link" href="{{ $tickets->previousPageUrl() }}">&laquo;</a>
            </li>
            <li>
              <form method="post" action="{{ url('paginationHelper') }}" style="position: absolute;">
                @csrf
                <input type="number" name="page" id="pageNumber" class="form-control" min="1" max="{{ $tickets->lastPage() }}" value="{{ $tickets->currentPage() }}"/>
              </form>
            </li>
            <li class="page-item">
              <p class="lead" style="padding: 0.2vw 0.4vw 0.2vw 0.4vw;"> {{ __('dashboard_tickets.pagination_of', ['last_page' => $tickets->lastPage()]) }}</p>
            </li>
            <li class="page-item">
              <a class="page-link" href="{{ $tickets->nextPageUrl() }}">&raquo;</a>
            </li>
          </ul>
        </nav>
        <thead>
          <tr>
            <td><b><a href="{{ url(url()->current()).'?sort=department_ticketID&order='.$order }}"><i class="{{ $sort == 'department_ticketID' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_ID') }}</b></td>
            <td style="width: 5%"><b><a href="{{ url(url()->current()).'?sort=ticket_status&order='.$order }}"><i class="{{ $sort == 'ticket_status' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_status') }}</b></td>
            <td><b><a href="{{ url(url()->current()).'?sort=zone&order='.$order }}"><i class="{{ $sort == 'zone' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_zone') }}</b></td>
            <td><b><a href="{{ url(url()->current()).'?sort=position&order='.$order }}"><i class="{{ $sort == 'position' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_position') }}</b></td>
            <td><b><a href="{{ url(url()->current()).'?sort=problem&order='.$order }}"><i class="{{ $sort == 'problem' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_problem') }}</b></td>
            <td><b><a href="{{ url(url()->current()).'?sort=device_name&order='.$order }}"><i class="{{ $sort == 'device_name' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_device') }}</b</td>
            <td style="width: 9%"><b><a href="{{ url(url()->current()).'?sort=date_created&order='.$order }}"><i class="{{ $sort == 'date_created' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_date_created') }}</b></td>
            @if (strpos(url()->current(), 'closed') == true)
              <td style="width: 9%"><b><a href="{{ url(url()->current()).'?sort=date_closed&order='.$order }}"><i class="{{ $sort == 'date_closed' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_date_closed') }}</b></td>
            @else
              <td style="width: 9%"><b><a href="{{ url(url()->current()).'?sort=date_modified&order='.$order }}"><i class="{{ $sort == 'date_modified' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_date_modified') }}</b></td>
            @endif
              <td style="width: 12%"><b><a href="{{ url(url()->current()).'?sort=owner&order='.$order }}"><i class="{{ $sort == 'owner' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> {{ __('dashboard_tickets.table_owner') }}</b></td>
          </tr>
        </thead>
        @foreach ($tickets as $ticket)
          @if ($ticket->priority == 4)
            <tr class='clickable-row' data-href="{{ url ('ticket/'.$ticket->ticketID) }}" style="background-color: #ff7f7f">
          @elseif ($ticket->priority == 0)
            <tr class='clickable-row' data-href="{{ url ('ticket/'.$ticket->ticketID) }}" style="background-color: #d4ebf2">
          @else
            <tr class='clickable-row' data-href="{{ url ('ticket/'.$ticket->ticketID) }}">
          @endif
          <td><strong><a href="{{ url ('ticket/'.$ticket->ticketID) }}" class="link-success text-decoration-none">{{ $ticket->department_ticketID }}</a></strong></td>
          <td>
            @switch ($ticket->ticket_status)
              @case (-1)
                <span class='badge rounded-pill bg-primary'>{{ __('dashboard_tickets.filter_awaiting') }}</span>
                @break
              @case (0)
                <span class='badge rounded-pill bg-success'>{{ __('dashboard_tickets.filter_new') }}</span>
                @break
              @case (1)
                <span class='badge rounded-pill bg-warning'>{{ __('dashboard_tickets.filter_in_progress') }}</span>
                @break
              @case (2)
                <span class='badge rounded-pill bg-danger'>{{ __('dashboard_tickets.filter_closed') }}</span>
                @break
            @endswitch
          </td>
          <td><strong><a href="{{ url ('ticket/'.$ticket->ticketID) }}" class="link-success text-decoration-none">{{ $ticket->zone }}</a></strong></td>
          <td>{{ $ticket->position }}</td>
          <td style="width: 15%"><strong><a href="{{ url ('ticket/'.$ticket->ticketID) }}" class="link-success text-decoration-none">{{ $ticket->problem }}</a></strong></td>
          <td>{{ $ticket->device_name }}</td>
          <td>{{ date('d-m-Y H:i', strtotime($ticket->date_created)) }}</td>
          @if (strpos(url()->current(), 'closed') == true)
            <td>{{ date('d-m-Y H:i', strtotime($ticket->date_closed)) }}</td>
          @else
            <td>{{ date('d-m-Y H:i', strtotime($ticket->date_modified)) }}</td>
          @endif
            <td>{{ $ticket->owner }}</td>
          </tr>
        @endforeach
      </table>
      <nav aria-label="paging" style="padding-left: 1vw; margin-bottom: -1.5vw">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="{{ $tickets->previousPageUrl() }}">&laquo;</a>
          </li>
          <li>
            <form method="post" action="{{ url('paginationHelper') }}">
              @csrf
              <input type="number" name="page" id="pageNumber" class="form-control" min="1" max="{{ $tickets->lastPage() }}" value="{{ $tickets->currentPage() }}"/>
            </form>
          </li>
          <li class="page-item">
            <p class="lead" style="padding: 0.2vw 0.4vw 0.2vw 0.4vw;"> {{ __('dashboard_tickets.pagination_of', ['last_page' => $tickets->lastPage()]) }}</p>
          </li>
          <li class="page-item">
            <a class="page-link" href="{{ $tickets->nextPageUrl() }}">&raquo;</a>
          </li>
        </ul>
      </nav>
    @else
      <p class="h2 text-center" style="padding-top: 1vw">{{ __('dashboard_tickets.table_nothing_found') }}</p>
    @endif​
  </div>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
  </script>
@endsection

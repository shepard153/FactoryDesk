@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <a href="{{ url ('my_tickets/') }}" class="btn btn-warning">{{ __('dashboard_tickets.filter_in_progress') }}</a>
  <a href="{{ url ('my_tickets/closed') }}" class="btn btn-danger">{{ __('dashboard_tickets.filter_closed') }}</a>
  <div class="row rounded shadow" style="background: white; margin: 1vw 0vw 0vw 0vw">
    <div class="col-sm-4" style="min-width: 380px">
      <p class="fs-3 border-bottom text-center" style="margin: 0.6vw 0vw 0.5vw 0.3vw">{{ __('dashboard_tickets.my_statistics') }}</p>
      <canvas id="doughnutChart" style="width: 400px; margin-left: auto; margin-right: auto"></canvas>
    </div>
    <div class="col-sm-8">
      @if ($latestTickets->count() > 0)
        <table class="table table-hover">
          <caption class="fs-3 border-bottom text-center" style="caption-side: top;">{{ str_contains(url()->current(), 'closed') ? __('dashboard_tickets.last_closed') : __('dashboard_tickets.last_taken') }}</caption>
          <thead>
            <tr>
              <td style="width: 5%"><b>{{ __('dashboard_tickets.table_status') }}</b></td>
              <td><b>{{ __('dashboard_tickets.table_zone') }}</b></td>
              <td><b>{{ __('dashboard_tickets.table_position') }}</b></td>
              <td><b>{{ __('dashboard_tickets.table_problem') }}</b></td>
              <td><b>{{ __('dashboard_tickets.table_device') }}</b</td>
              <td style="width: 10%"><b>{{ __('dashboard_tickets.table_date_created') }}</b></td>
              @if (strpos(url()->current(), 'closed') == true)
                <td style="width: 9%"><b>{{ __('dashboard_tickets.table_date_closed') }}</b></td>
              @else
                <td style="width: 9%"><b>{{ __('dashboard_tickets.table_date_modified') }}</b></td>
              @endif
            </tr>
          </thead>
          @foreach ($latestTickets as $latest)
            @if ($latest->priority == 4)
              <tr class='clickable-row' data-href="{{ url ('ticket/'.$latest->ticketID) }}" style="background-color: #ff7f7f">
            @elseif ($latest->priority == 0)
              <tr class='clickable-row' data-href="{{ url ('ticket/'.$latest->ticketID) }}" style="background-color: #d4ebf2">
            @else
              <tr class='clickable-row' data-href="{{ url ('ticket/'.$latest->ticketID) }}">
            @endif
            <td>
              @switch ($latest->ticket_status)
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
            <td><strong><a href="{{ url ('ticket/'.$latest->ticketID) }}" class="link-success text-decoration-none">{{ $latest->zone }}</a></strong></td>
            <td>{{ $latest->position }}</td>
            <td style="width: 20%"><strong><a href="{{ url ('ticket/'.$latest->ticketID) }}" class="link-success text-decoration-none">{{ $latest->problem }}</a></strong></td>
            <td>{{ $latest->device_name }}</td>
            <td>{{ date('d-m-Y', strtotime($latest->date_created)) }}</td>
            @if (strpos(url()->current(), 'closed') == true)
              <td>{{ date('d-m-Y', strtotime($latest->date_closed)) }}</td>
            @else
              <td>{{ date('d-m-Y', strtotime($latest->date_modified)) }}</td>
            @endif
             </tr>
          @endforeach
        </table>
      @else
        <p class="h2 text-center" style="padding-top: 1vw">Brak elementów do wyświetlenia.</p>
      @endif​
    </div>
  </div>

  <div class="col rounded shadow" style="background: white; margin: 1vw 0vw 0.5vw 0vw; padding: 0vw 0.5vw 0vw 0.5vw">
    @if ($tickets->count() > 0)
      <table class="table table-hover">
        <caption class="fs-3 border-bottom" style="caption-side: top; text-align: center;">{{ str_contains(url()->current(), 'closed') ? __('dashboard_tickets.all_closed') : __('dashboard_tickets.all_taken') }}</caption>
        <thead>
          <tr>
            <td style="width: 5%"><b>{{ __('dashboard_tickets.table_status') }}</b></td>
            <td style="width: 7%"><b>{{ __('dashboard_tickets.table_zone') }}</b></td>
            <td style="width: 13%"><b>{{ __('dashboard_tickets.table_position') }}</b></td>
            <td><b>{{ __('dashboard_tickets.table_problem') }}</b></td>
            <td style="width: 10%"><b>{{ __('dashboard_tickets.table_device') }}</b</td>
            <td style="width: 10%"><b>{{ __('dashboard_tickets.table_date_created') }}</b></td>
              @if (strpos(url()->current(), 'closed') == true)
                <td style="width: 10%"><b>{{ __('dashboard_tickets.table_date_closed') }}</b></td>
              @else
                <td style="width: 10%"><b>{{ __('dashboard_tickets.table_date_modified') }}</b></td>
              @endif
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
          <td>
            @switch ($ticket->ticket_status)
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
          <td style="width: 20%"><strong><a href="{{ url ('ticket/'.$ticket->ticketID) }}" class="link-success text-decoration-none">{{ $ticket->problem }}</a></strong></td>
          <td>{{ $ticket->device_name }}</td>
          <td>{{ date('d-m-Y', strtotime($ticket->date_created)) }}</td>
            @if (strpos(url()->current(), 'closed') == true)
              <td>{{ date('d-m-Y', strtotime($ticket->date_closed)) }}</td>
            @else
              <td>{{ date('d-m-Y', strtotime($ticket->date_modified)) }}</td>
            @endif
          </tr>
        @endforeach
      </table>
      <nav aria-label="paging" style="padding-left: 1vw; margin-bottom: -1vw">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="{{ $tickets->previousPageUrl() }}">&laquo;</a>
          </li>
          <li>
            <form method="get" action="{{ url(url()->current()) }}">
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

    const ctx = $('#doughnutChart');
    const myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("dashboard_tickets.filter_in_progress") }}', '{{ __("dashboard_tickets.filter_closed") }}'],
            datasets: [{
                data: ['{{ $ticketsOpen }}', '{{ $ticketsClosed }}'],
                backgroundColor: ['Orange', '#66AF66'],
            },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            }
        },
    });
</script>
@endsection

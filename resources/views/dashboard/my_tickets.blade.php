@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <a href="{{ url ('my_tickets/') }}" class="btn btn-warning">Podjęte</a>
    <a href="{{ url ('my_tickets/closed') }}" class="btn btn-danger">Zamknięte</a>
    <div class="row rounded shadow" style="background: white; margin: 1vw 0vw 0vw 0vw">
        <div class="col-4">
            <div class="row">
            <p class="fs-3 border-bottom text-center" style="margin: 0.6vw 0vw 0.5vw 0.3vw">Moje statystyki</p>
                <div class="col">
                    <p class="fs-5 text-center">Ilość zamkniętych zgłoszeń</p>
                    <canvas id="closedCanvas" style="margin-left: auto;margin-right: auto;display: block;" width="200" height="200"></canvas>
                </div>
                <div class="col">
                    <p class="fs-5 text-center">Ilość aktywnych zgłoszeń</p>
                    <canvas id="openCanvas" style="margin-left: auto;margin-right: auto;display: block;" width="200" height="200"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="fs-5 text-center">Procent rozwiązanych</p>
                    <canvas id="pieChartCanvas" style="margin-left: auto;margin-right: auto;display: block;" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-8">
        @if ($latestTickets->count() > 0)
        <table class="table table-hover">
            <caption class="fs-3 border-bottom" style="caption-side: top; text-align: center;">Ostatnio {{ str_contains(url()->current(), 'closed') ? 'zamknięte' : 'podjęte' }} zgłoszenia</caption>
            <thead>
                <tr>
                    <td style="width: 5%"><b>Status</b></td>
                    <td><b>Obszar</b></td>
                    <td><b>Stanowisko</b></td>
                    <td><b>Problem</b></td>
                    <td><b>Komputer</b</td>
                    <td style="width: 10%"><b>Data zgłoszenia</b></td>
                    @if (strpos(url()->current(), 'closed') == true)
                        <td style="width: 9%"><b>Data zamknięcia</b></td>
                    @else
                       <td style="width: 9%"><b>Data modyfikacji</b></td>
                    @endif
                </tr>
            </thead>
            @foreach ($latestTickets as $latest)
                @if ($loop->iteration > 10)
                    @break
                @endif
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
                            <span class='badge rounded-pill bg-success'>Nowe</span>
                            @break
                        @case (1)
                            <span class='badge rounded-pill bg-warning'>Podjęte</span>
                            @break
                        @case (2)
                            <span class='badge rounded-pill bg-danger'>Zamknięte</span>
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
            <caption class="fs-3 border-bottom" style="caption-side: top; text-align: center;">Wszystkie {{ str_contains(url()->current(), 'closed') ? 'zamknięte' : 'podjęte' }} zgłoszenia</caption>
            <thead>
                <tr>
                    <td style="width: 5%"><b>Status</b></td>
                    <td style="width: 7%"><b>Obszar</b></td>
                    <td style="width: 13%"><b>Stanowisko</b></td>
                    <td><b>Problem</b></td>
                    <td style="width: 10%"><b>Komputer</b</td>
                    <td style="width: 10%"><b>Data zgłoszenia</b></td>
                    @if (strpos(url()->current(), 'closed') == true)
                        <td style="width: 10%"><b>Data zamknięcia</b></td>
                    @else
                       <td style="width: 10%"><b>Data modyfikacji</b></td>
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
                            <span class='badge rounded-pill bg-success'>Nowe</span>
                            @break
                        @case (1)
                            <span class='badge rounded-pill bg-warning'>Podjęte</span>
                            @break
                        @case (2)
                            <span class='badge rounded-pill bg-danger'>Zamknięte</span>
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
                    <p class="lead" style="padding: 0.2vw 0.4vw 0.2vw 0.4vw;"> z {{ $tickets->lastPage() }}</p>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $tickets->nextPageUrl() }}">&raquo;</a>
                </li>
            </ul>
        </nav>
        @else
            <p class="h2 text-center" style="padding-top: 1vw">Brak elementów do wyświetlenia.</p>
        @endif​
    </div>

    <script src="{{ asset('public/js/rpie.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });

		var pieChartObject = {
						pie: 'stroke',
						values: [{{ $percentageSolved }}],
						colors: ['#4CAF50'],
						isStrokePie: {
							stroke: 20,
                            overlayStroke: true,
							strokeStartEndPoints: 'Yes',
							strokeAnimation: true,
							strokeAnimationSpeed: 15,
							fontSize: '40px',
							textAlignement: 'center',
							fontFamily: 'Arial',
							fontWeight: 'bold',
						}
					  };

        generatePieGraph('pieChartCanvas', pieChartObject);

        var c = document.getElementById("closedCanvas");
        var ctx = c.getContext("2d");
        ctx.beginPath();
        var grd = ctx.createRadialGradient(100, 95, 40, 100, 100, 100);
        grd.addColorStop(0, "#4CAF50");
        grd.addColorStop(1, "green");
        ctx.fillStyle = grd;
        ctx.strokeStyle = "black";
        ctx.font = "40px Georgia";
        ctx.lineWidth = 10;
        ctx.arc(100, 100, 90, 0, 2 * Math.PI);
        ctx.fill();
        ctx.beginPath();
        ctx.fillStyle = "#4c4c4c";
        ctx.textAlign = 'center';
        ctx.fillText({{ $ticketsClosed }}, 90, 105);
        ctx.fill();

        var c = document.getElementById("openCanvas");
        var ctx = c.getContext("2d");
        ctx.beginPath();
        var grd = ctx.createRadialGradient(100, 95, 40, 100, 100, 100);
        grd.addColorStop(1, "#ffae1a");
        grd.addColorStop(0, "#ffd667");
        ctx.fillStyle = grd;
        ctx.strokeStyle = "black";
        ctx.font = "40px Georgia";
        ctx.lineWidth = 10;
        ctx.arc(100, 100, 90, 0, 2 * Math.PI);
        ctx.fill();
        ctx.beginPath();
        ctx.fillStyle = "#4c4c4c";
        ctx.fillText({{ $ticketsOpen }}, 90, 105);
        ctx.fill();
    </script>
@endsection

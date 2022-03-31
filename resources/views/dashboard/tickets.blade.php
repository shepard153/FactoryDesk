@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <a href="{{ url ('tickets/new') }}" class="btn btn-success">Nowe</a>
    <a href="{{ url ('tickets/taken') }}" class="btn btn-warning">Podjęte</a>
    <a href="{{ url ('tickets/closed') }}" class="btn btn-danger">Zamknięte</a>
    <a href="{{ url ('tickets/active') }}" class="btn btn-primary">Aktywne</a>
    <div class="col rounded shadow" style="background: white; margin-top: 1vw;">
        @if ($tickets->count() > 0)
        <table class="table table-hover">
            <div id="paging"><p class="lead" style="padding: 0.7vw 0px 0px 1vw;">Wyświetlane {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} z {{ $tickets->total() }} wyników.</p></div>
            <thead>
                <tr>
                    <td style="width: 5%"><b><a href="{{ url(url()->current()).'?sort=ticket_status&order='.$order }}"><i class="{{ $sort == 'ticket_status' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Status</b></td>
                    <td><b><a href="{{ url(url()->current()).'?sort=zone&order='.$order }}"><i class="{{ $sort == 'zone' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Obszar</b></td>
                    <td><b><a href="{{ url(url()->current()).'?sort=position&order='.$order }}"><i class="{{ $sort == 'position' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Stanowisko</b></td>
                    <td><b><a href="{{ url(url()->current()).'?sort=problem&order='.$order }}"><i class="{{ $sort == 'problem' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Problem</b></td>
                    <td><b><a href="{{ url(url()->current()).'?sort=device_name&order='.$order }}"><i class="{{ $sort == 'device_name' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Komputer</b</td>
                    <td style="width: 9%"><b><a href="{{ url(url()->current()).'?sort=date_created&order='.$order }}"><i class="{{ $sort == 'date_created' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Data zgłoszenia</b></td>
                    @if (strpos(url()->current(), 'closed') == true)
                        <td style="width: 9%"><b><a href="{{ url(url()->current()).'?sort=date_closed&order='.$order }}"><i class="{{ $sort == 'date_closed' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Data zamknięcia</b></td>
                    @else
                       <td style="width: 9%"><b><a href="{{ url(url()->current()).'?sort=date_modified&order='.$order }}"><i class="{{ $sort == 'date_modified' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Data modyfikacji</b></td>
                    @endif
                    <td style="width: 12%"><b><a href="{{ url(url()->current()).'?sort=owner&order='.$order }}"><i class="{{ $sort == 'owner' ? $arrows : 'fa-solid fa-arrows-up-down'}}"></i></a> Osoba odpowiedzialna</b></td>
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
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
@endsection

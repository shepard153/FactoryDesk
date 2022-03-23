@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
  <meta http-equiv="refresh" content="30">
        <div class="row justify-content-center">
          <div class="col rounded shadow" style="background: white; margin: 0vw 1vw 0vw 1vw;">
            <p class="fs-3 border-bottom" style="text-align: center;">Najnowsze zgłoszenia</p>
            <table class="table table-hover">
              <thead>
                <tr>
                  <td><b>Problem</b></td>
                  <td><b>Obszar</b></td>
                  <td><b>Stanowisko</b></td>
                  <td><b>Priorytet</b></td>
                  <td><b>Data zgłoszenia</b></td>
                  <td><b>Status</b></td>
                </tr>
              </thead>
            @if ($dashboard['newest'] != null)
              @foreach($dashboard['newest'] as $newest)
                @if ($loop->iteration > 5)
                  @break
                @endif
                @if ($newest->priority == 4)
                  <tr class='clickable-row' data-href='ticket/{{ $newest->ticketID }}' style="background-color: #ff7f7f">
                @elseif ($newest->priority == 0)
                  <tr class='clickable-row' data-href='ticket/{{ $newest->ticketID }}' style="background-color: #d4ebf2">
                @else
                  <tr class='clickable-row' data-href='ticket/{{ $newest->ticketID }}'>
                @endif
                  <td>{{ $newest->problem }}</td>
                  <td>{{ $newest->zone }}</td>
                  <td>{{ $newest->position }}</td>
                  <td>
                      @switch ($newest->priority )
                        @case (0)
                          Powiadomienie
                          @break
                        @case (1)
                          Niski
                          @break;
                        @case (2)
                          Standardowy
                          @break
                        @case (3)
                          Wysoki
                          @break
                        @case (4)
                          Krytyczny
                          @break
                        @default ----------
                    @endswitch
                  </td>
                  <td>{{ $newest->date_created }}</td>
                  <td>
                      @if ($newest->ticket_status == '0')
                        <span class='badge rounded-pill bg-success'>Nowe</span>
                      @elseif ($newest->ticket_status == '1')
                        <span class='badge rounded-pill bg-warning'>Aktywne</span>
                      @elseif ($newest->ticket_status == '2')
                        <span class='badge rounded-pill bg-danger'>Zamknięte</span>
                      @endif
                  </td>
                </tr>
              @endforeach
            @else
              <p class="fs-2 text-center" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>
            @endif
            </table>
          </div>
        </div>
        <div class="row justify-content-center" style="margin-left: 2vw; margin-top: 1vw;">
          <div class="col-3 border border-success rounded shadow" style="background: white; max-width: 340px; margin-right: 3vw">
              <table class="table table-sm table-borderless">
                <thead>
                  <tr class="border-bottom">
                    <td class="removable"><img src="{{ asset('public/img/dashboard-icon.png') }}" class="rounded"></td>
                    <td style="vertical-align: center"><p class="fs-2" style="margin-bottom: 1.5vw">Statystyki</p></td>
                  </tr>
                  <tr>
                    <td><h5>Wszystkie zgłoszenia</h5></td>
                    <td><h2>{{ $dashboard['total'] }}</h2></td>
                  </tr>
                </thead>
                <tr>
                  <td><h5>Nowe</h5></td>
                  <td><h2>{{ $dashboard['total_new'] }}</h2></td>
                </tr>
                <tr>
                  <td><h5>Aktywne</h5></td>
                  <td><h2>{{ $dashboard['total_open'] }}</h2></td>
                </tr>
                <tr>
                  <td><h5>Zamknięte</h5></td>
                  <td><h2>{{ $dashboard['total_closed'] }}</h2></td>
                </tr>
              </table>
          </div>
          <div class="col-3 border border-success rounded shadow" style="background: white; max-width: 340px;">
            <p class="fs-2 border-bottom">Obszary z największą liczbą zgłoszeń</p>
            <table class="table table-sm table-borderless">
              @foreach($dashboard['mostProblematic'] as $mostProblematic)
                <tr>
                  <td style="text-align: left"><h5>{{ $mostProblematic->zone }}</h5></td>
                  <td class="text-end"><h4>{{ $mostProblematic->problematic }}</h4></td>
                </tr>
                @if ($loop->iteration == 5)
                  @break
                @endif
              @endforeach
            </table>
          </div>
          <div class="col-3 border border-success rounded shadow" style="background: white; max-width: 340px; margin-left: 3vw">
            <p class="fs-2 border-bottom">Najczęstsze problemy</p>
            <table class="table table table-borderless">
              @foreach($dashboard['topProblems'] as $topProblems)
                <tr>
                  <td style="text-align: left"><h5>{{ $topProblems->problem }}</h5></td>
                  <td class="text-end"><h4>{{ $topProblems->occurence }}</h4></td>
                </tr>
                @if ($loop->iteration == 5)
                  @break
                @endif
              @endforeach
            </table>
          </div>

<script>
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
@endsection

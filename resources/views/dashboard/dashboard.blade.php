@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <div class="col rounded shadow" style="background: white;">
        <p class="fs-3 border-bottom" style="text-align: center;">Najnowsze zgłoszenia</p>
        <table class="table table-hover" id="newestTable">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Status</td>
                    <td>Obszar</td>
                    <td>Stanowisko</td>
                    <td>Problem</td>
                    <td>Komputer</td>
                    <td>Data zgłoszenia</td>
                </tr>
            </thead>
            <tbody id="newestRows">
            @if (count($dashboard['newest']) > 0)
              @foreach($dashboard['newest'] as $newest)
                @if ($newest->priority == 4)
                  <tr class='clickable-row' data-href='ticket/{{ $newest->ticketID }}' style="background-color: #ff7f7f">
                @elseif ($newest->priority == 0)
                  <tr class='clickable-row' data-href='ticket/{{ $newest->ticketID }}' style="background-color: #d4ebf2">
                @else
                  <tr class='clickable-row' data-href='ticket/{{ $newest->ticketID }}'>
                @endif
                  <td>{{ $newest->department_ticketID }}</td>
                  <td>
                    @if ($newest->ticket_status == '-1')
                        <span class='badge rounded-pill bg-primary'>Do zatwierdzenia</span>
                    @elseif ($newest->ticket_status == '0')
                        <span class='badge rounded-pill bg-success'>Nowe</span>
                    @elseif ($newest->ticket_status == '1')
                        <span class='badge rounded-pill bg-warning'>Podjęte</span>
                    @endif
                  </td>
                  <td>{{ $newest->zone }}</td>
                  <td>{{ $newest->position }}</td>
                  <td>{{ $newest->problem }}</td>
                  <td>{{ $newest->device_name }}</td>
                  <td>{{ $newest->date_created }}</td>
                </tr>
              @endforeach
            @else
                <p class="fs-2 text-center" id="phpNothingNewMessage" style="padding: 0vw 0px 1vw 0px;">Brak nowych zgłoszeń.</p>
            @endif
                <p class="fs-2 text-center" id="nothingNewMessage" style="padding: 0vw 0px 1vw 0px; display: none">Brak nowych zgłoszeń.</p>
            </tbody>
        </table>
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
                        <td><h2 id="total">{{ $dashboard['total'] }}</h2></td>
                    </tr>
                </thead>
                <tr>
                    <td><h5>Nowe</h5></td>
                    <td><h2 id="total_new">{{ $dashboard['total_new'] }}</h2></td>
                </tr>
                <tr>
                    <td><h5>Aktywne</h5></td>
                    <td><h2 id="total_open">{{ $dashboard['total_open'] }}</h2></td>
                </tr>
                <tr>
                    <td><h5>Zamknięte</h5></td>
                    <td><h2 id="total_closed">{{ $dashboard['total_closed'] }}</h2></td>
                </tr>
            </table>
        </div>
        <div class="col-3 border border-success rounded shadow" style="background: white; max-width: 340px;">
            <p class="fs-2 border-bottom">Obszary z największą liczbą zgłoszeń</p>
            <table class="table table-sm table-borderless" id="mostProblematic">
                @foreach($dashboard['mostProblematic'] as $mostProblematic)
                    <tr>
                        <td style="text-align: left"><h5>{{ $mostProblematic->zone }}</h5></td>
                        <td class="text-end"><h4>{{ $mostProblematic->problematic }}</h4></td>
                    </tr>
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
                @endforeach
            </table>
        </div>
    </div>

<script>
    jQuery(document).ready(function($) {
        let refreshTime = "{{ $settings['dashboard_refreshTime'] }}" * 1000;
        update();
        setInterval(update, refreshTime);

        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });

    /**
     * Ajax function for Dashboard data. Once the page loads, dashboard data is being refreshed constantly
     * after time set in "refreshTime" variable.
     */
    function update(){
        $.ajax({
            type: "GET",
            url: "dashboard/ajax",
            dataType: 'json',
            success: function(dashboardData){
                $.each(dashboardData, function(key, value) {
                    $('#total').html(value['total']);
                    $('#total_new').html(value['total_new']);
                    $('#total_closed').html(value['total_closed']);
                    $('#total_open').html(value['total_open']);

                    $('#mostProblematic').empty();
                    $.each(value['mostProblematic'], function(key, value) {
                        $('#mostProblematic').append('<tr> \
                            <td style="text-align: left"><h5>'+ value['zone'] +'</h5></td> \
                            <td class="text-end"><h4>'+ value['problematic'] +'</h4></td> \
                        </tr>');
                    });

                    $('#topProblems').empty();
                    $.each(value['topProblems'], function(key, value) {
                        $('#topProblems').append('<tr> \
                            <td style="text-align: left"><h5>'+ value['zone'] +'</h5></td> \
                            <td class="text-end"><h4>'+ value['occurence'] +'</h4></td> \
                        </tr>');
                    });

                    let row, priority, status;
                    $('#phpNothingNewMessage').hide();
                    if (value['newest'].length === 0) {
                        $('#nothingNewMessage').css('display', 'block');
                        $('#newestTable').hide();
                    }
                    else{
                        $('#nothingNewMessage').hide();
                        $('#newestTable').css('display', 'table');
                        $('#newestRows').empty();
                        $.each(value['newest'], function(key, value) {
                            switch (value['priority']) {
                                case '4':
                                    row = "<tr class='clickable-row' data-href='ticket/" + value['ticketID'] + "' style='background-color: #ff7f7f'>";
                                    priority = "Krytyczny";
                                    break;
                                case '0':
                                    row = "<tr class='clickable-row' data-href='ticket/" + value['ticketID'] + "' style='background-color: #d4ebf2'>";
                                    priority = "Powiadomienie";
                                    break;
                                default:
                                    row = "<tr class='clickable-row' data-href='ticket/" + value['ticketID'] + "'>";
                                    priority = "Standardowy";
                                    break;
                            }

                            switch (value['ticket_status']){
                                case '-1':
                                    status = "<span class='badge rounded-pill bg-primary'>Do zatwierdzenia</span>";
                                    break;
                                case '0':
                                    status = "<span class='badge rounded-pill bg-success'>Nowe</span>";
                                    break;
                                case '1':
                                    status = "<span class='badge rounded-pill bg-warning'>Podjęte</span>";
                                    break;
                            }

                            $(".clickable-row").click(function() {
                                window.location = $(this).data("href");
                            });

                            date = new Date(value['date_created']);
                            date = date.getFullYear() + "-" + ('0' + (date.getMonth()+1)).slice(-2) + "-" + ('0' + date.getDate()).slice(-2) + " " +
                                    ('0' + date.getHours()).slice(-2) + ":" + ('0' + date.getMinutes()).slice(-2) + ":" + ('0' + date.getSeconds()).slice(-2);

                            $('#newestRows').append(row +
                                '<td>' + value['department_ticketID'] + '</td> \
                                <td>' + status + '</td> \
                                <td>' + value['zone'] + '</td> \
                                <td>' + value['position'] + '</td> \
                                <td>' + value['problem'] + '</td> \
                                <td>' + value['device_name'] + '</td> \
                                <td>' + date  + '</td> \
                                </tr>'
                            );
                        });
                    }
                });
            },
            error: function (response) {
                $("#newestTable").html('Błąd JavaScript. Aby odświeżyć dane, załaduj stronę ponownie.');
            }
        });
    }
</script>
@endsection

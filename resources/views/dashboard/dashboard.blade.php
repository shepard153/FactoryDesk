@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <div class="col col-lg-12 rounded shadow" style="background: white;">
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
    <div class="row mt-2">
        <div class="col col-lg-3">
            <div class="col">
                <label class="form-label" for="startDate">Data</label>
                <input type="date" class="form-control" name="startDate" id="startDate" onchange="datePick(this);"/>
            </div>
            <div class="card border-primary mt-2">
                <div class="card-body">
                    <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-column" style="color: blue"></i> Zgłoszeń łącznie</h5>
                    <span class="h2 font-weight-bold mb-0" id="allCard"></span>
                </div>
            </div>
            <div class="card border-warning mt-2">
                <div class="card-body">
                    <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-bars-progress" style="color: orange"></i> W realizacji</h5>
                    <span class="h2 font-weight-bold mb-0" id="allOpenCard"></span>
                </div>
            </div>
            <div class="card border-info mt-2">
                <div class="card-body">
                    <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-area" style="color: cyan"></i> Najwięcej zgłoszeń z</h5>
                    <span class="h2 font-weight-bold mb-0" id="mostProblematicCard"></span>
                </div>
            </div>
            <div class="card border-dark mt-2">
                <div class="card-body">
                    <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-diagram-successor" style="color: dark"></i> Najczęstszy problem</h5>
                    <span class="h2 font-weight-bold mb-0" id="topProblemCard"></span>
                </div>
            </div>
        </div>
        <div class="col col-lg-9">
            <canvas id="graphCanvas" height="auto"></canvas>
            <div id="loadingSpinner" class="text-center">
                <img src="{{ asset('public/img/loading.gif')}}"/>
            </div>
        </div>
    </div>

<script>
    jQuery(document).ready(function($) {
        let refreshTime = "{{ $settings['dashboard_refreshTime'] }}" * 1000;
        update();
        showChart();
        setInterval(function () {showChart($('#startDate').val());}, refreshTime);
        setInterval(update, refreshTime);
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });

    function datePick(e) {
        showChart(e.value);
    }

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
                $('#allCard').text(dashboardData['dashboardData']['all']);
                $('#allOpenCard').text(dashboardData['dashboardData']['allOpen']);
                $('#mostProblematicCard').text(dashboardData['dashboardData']['mostProblematic'][0]['zone']);
                $('#topProblemCard').text(dashboardData['dashboardData']['topProblem'][0]['problem']);

                $.each(dashboardData, function(key, value) {

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

                            $(".clickable-row").click(function() {
                                window.location = $(this).data("href");
                            });
                        });
                    }
                });
            },
            error: function (response) {
                $("#newestTable").html('Błąd JavaScript. Aby odświeżyć dane, załaduj stronę ponownie.');
            }
        });
    }


    /**
     * Draw chart function.
     */
    function showChart(startDate = null)
    {
        if (startDate == null || startDate == ''){
            startDate = null;
        }

        $.ajax({
            type: "GET",
            url: 'dashboard/chart/' + startDate,
            dataType: 'json',
            beforeSend: function() {
                $("#graphCanvas").hide();
                $("#loadingSpinner").show();
            },
            success: function(data) {
                $("#loadingSpinner").hide();
                $("#graphCanvas").show();

                const ctx = $('#graphCanvas');

                let chartStatus = Chart.getChart("graphCanvas");
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }

                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data['labels'],
                        datasets: [{
                            label: 'Nowe: ' + data['chartLegendNew'],
                            data: data['new'],
                            fill: false,
                            borderColor: 'green',
                        },
                        {
                            label: 'Podjęte: ' + data['chartLegendOpen'],
                            data: data['opened'],
                            fill: false,
                            borderColor: 'orange',
                        },
                        {
                            label: 'Zamknięte: ' + data['chartLegendClosed'],
                            data: data['closed'],
                            fill: false,
                            borderColor: 'red',
                        },
                        ]
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: true}
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        label = label.split(':')[0];

                                        if (label) {
                                            label += ': ';
                                        }

                                        if (context.parsed.y !== null) {
                                            label += context.parsed.y;
                                        }

                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }
</script>
@endsection

@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col col-lg-12 rounded shadow" style="background: white;">
    <p class="fs-3 border-bottom" style="text-align: center;">{{ __('dashboard_main.newest_tickets') }}</p>
    <table class="table table-hover" id="newestTable">
      <thead>
        <tr>
          <td>{{ __('dashboard_main.table_ID') }}</td>
          <td>{{ __('dashboard_main.table_status') }}</td>
          <td>{{ __('dashboard_main.table_zone') }}</td>
          <td>{{ __('dashboard_main.table_position') }}</td>
          <td>{{ __('dashboard_main.table_problem') }}</td>
          <td>{{ __('dashboard_main.table_device') }}</td>
          <td>{{ __('dashboard_main.table_date') }}</td>
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
                <span class='badge rounded-pill bg-primary'>{{ __('dashboard_main.status_pill_awaiting') }}</span>
              @elseif ($newest->ticket_status == '0')
                <span class='badge rounded-pill bg-success'>{{ __('dashboard_main.status_pill_new') }}</span>
              @elseif ($newest->ticket_status == '1')
                <span class='badge rounded-pill bg-warning'>{{ __('dashboard_main.status_pill_in_progress') }}</span>
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
          <p class="fs-2 text-center" id="phpNothingNewMessage" style="padding: 0vw 0px 1vw 0px;">{{ __('dashboard_main.table_no_new_tickets') }}</p>
        @endif
          <p class="fs-2 text-center" id="nothingNewMessage" style="padding: 0vw 0px 1vw 0px; display: none">{{ __('dashboard_main.table_no_new_tickets') }}</p>
      </tbody>
    </table>
  </div>
  <div class="row mt-2">
    <div class="col col-lg-3">
      <div class="col">
        <label class="form-label" for="startDate">{{ __('dashboard_main.chart_start_date') }}</label>
        <input type="date" class="form-control" name="startDate" id="startDate" onchange="datePick(this);"/>
      </div>
      <div class="card border-primary mt-2">
        <div class="card-body">
          <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-column" style="color: blue"></i> {{ __('dashboard_main.card_tickets_total') }}</h5>
          <span class="h2 font-weight-bold mb-0" id="allCard"></span>
        </div>
      </div>
      <div class="card border-warning mt-2">
        <div class="card-body">
          <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-bars-progress" style="color: orange"></i> {{ __('dashboard_main.card_tickets_in_progress') }}</h5>
          <span class="h2 font-weight-bold mb-0" id="allOpenCard"></span>
        </div>
      </div>
      <div class="card border-info mt-2">
        <div class="card-body">
          <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-area" style="color: cyan"></i> {{ __('dashboard_main.card_most_problematic') }}</h5>
          <span class="h2 font-weight-bold mb-0" id="mostProblematicCard"></span>
        </div>
      </div>
      <div class="card border-dark mt-2">
        <div class="card-body">
          <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-diagram-successor" style="color: dark"></i> {{ __('dashboard_main.card_top_problem') }}</h5>
          <span class="h2 font-weight-bold mb-0" id="topProblemCard"></span>
        </div>
      </div>
    </div>
    <div class="col col-lg-9">
      <canvas id="graphCanvas" height="auto"></canvas>
      <div id="loadingSpinner" class="text-center">
        <img src="{{ asset('img/loading.gif')}}"/>
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

                    let row, status;
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
                                case 4:
                                    row = "<tr class='clickable-row' data-href='ticket/" + value['ticketID'] + "' style='background-color: #ff7f7f'>";
                                    break;
                                case 0:
                                    row = "<tr class='clickable-row' data-href='ticket/" + value['ticketID'] + "' style='background-color: #d4ebf2'>";
                                    break;
                                default:
                                    row = "<tr class='clickable-row' data-href='ticket/" + value['ticketID'] + "'>";
                                    break;
                            }

                            switch (value['ticket_status']){
                                case -1:
                                    status = "<span class='badge rounded-pill bg-primary'>{{ __('dashboard_main.status_pill_awaiting') }}</span>";
                                    break;
                                case 0:
                                    status = "<span class='badge rounded-pill bg-success'>{{ __('dashboard_main.status_pill_new') }}</span>";
                                    break;
                                case 1:
                                    status = "<span class='badge rounded-pill bg-warning'>{{ __('dashboard_main.status_pill_in_progress') }}</span>";
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
                $("#newestTable").html('{{ __("dashboard_main.javascript_error_message") }}');
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
                            label: '{{ __("dashboard_main.card_tickets_new") }}: ' + data['chartLegendNew'],
                            data: data['new'],
                            fill: false,
                            borderColor: 'green',
                            tension: 0.4
                        },
                        {
                            label: '{{ __("dashboard_main.card_tickets_in_progress") }}: ' + data['chartLegendOpen'],
                            data: data['opened'],
                            fill: false,
                            borderColor: 'orange',
                            tension: 0.4
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
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

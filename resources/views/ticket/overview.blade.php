@extends('ticket/ticket_template')

@section('title', 'RUGDesk')

@section('navbar')
  @parent

@endsection

@section('content')
  <p class="fs-4 border-bottom text-center" id="header"></p>
  <div class="col-3">
    <label class="form-label">{{ __('main_page.overview_select_department') }}</label>
    <select id="overviewDepartment" class="form-select">
      <option value="All">{{ __('main_page.overview_all_departments') }}</option>
      @foreach ($departmentList as $department)
        <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col col-lg-12">
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
        <p class="fs-2 text-center" id="nothingNewMessage" style="padding: 0vw 0px 1vw 0px; display: none">{{ __('dashboard_main.table_no_new_tickets') }}</p>
      </tbody>
    </table>
  </div>
  <div class="row mt-2">
    <div class="col col-lg-3 mt-3">
      <div class="card border-primary mt-3">
        <div class="card-body">
          <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-line" style="color: blue"></i> {{ __('main_page.overview_tickets_total') }}</h5>
          <span class="h2 font-weight-bold mb-0" id="allCard"></span>
        </div>
      </div>
      <div class="card border-warning mt-3">
        <div class="card-body">
          <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-area" style="color: orange"></i> {{ __('main_page.overview_tickets_in_progress') }}</h5>
          <span class="h2 font-weight-bold mb-0" id="allOpenCard"></span>
        </div>
      </div>
    </div>
    <div class="col col-lg-9">
      <canvas id="graphCanvas" height="120"></canvas>
      <div id="loadingSpinner" class="text-center">
        <img src="{{ asset('public/img/loading.gif')}}"/>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {
        showChart('{{ $defaultDepartment}}');
        if ('{{ $defaultDepartment}}' == 'All'){
            $('#header').text('{{ __("main_page.overview_heading_all") }}');
        }
        else{
            $('#header').text('{{ __("main_page.overview_heading", ["department" => $defaultDepartment]) }}');
        }

        setInterval(function () {showChart($('#overviewDepartment').val());}, 30000);
    });

    $('#overviewDepartment').on('change', function() {
        department = $(this).val();
        if (department == 'All'){
            $('#header').text('{{ __("main_page.overview_heading_all") }}');
        }
        else{
            $('#header').text('{{ __("main_page.overview_heading", ["department" => '']) }}' + department);
        }
        showChart(department);
    })

    /**
     * Draw chart function.
     */
    function showChart(department)
    {
        $.ajax({
            type: "GET",
            url: "overview/chartData/" + department,
            dataType: 'json',
            success: function(data){
                $("#loadingSpinner").hide();
                $("#graphCanvas").show();
                $('#allCard').text(data['all']);
                $('#allOpenCard').text(data['allOpen']);

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
                            label: '{{ __("main_page.overview_tickets_new") }}',
                            data: data['new'],
                            fill: false,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            tension: 0.1
                        },
                        {
                            label: '{{ __("main_page.overview_tickets_in_progress") }}',
                            data: data['opened'],
                            fill: false,
                            borderColor: 'rgb(255, 159, 64)',
                            backgroundColor: 'rgba(255, 159, 64, 0.5)',
                            tension: 0.1
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
                    }
                });

                if (data['newest'].length === 0) {
                    $('#nothingNewMessage').css('display', 'block');
                    $('#newestTable').hide();
                }
                else{
                    $('#nothingNewMessage').hide();
                    $('#newestTable').css('display', 'table');
                    $('#newestRows').empty();
                    $.each(data['newest'], function(key, value) {
                        switch (value['priority']) {
                            case '4':
                                row = "<tr class='clickable-row' data-href='ticket/" + value['ticketID'] + "' style='background-color: #ff7f7f'>";
                                break;
                            case '0':
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
                    });
                }
            }
        });
    }
  </script>
@endsection

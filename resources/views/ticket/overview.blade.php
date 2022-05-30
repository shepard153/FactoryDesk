@extends('ticket/ticket_template')

@section('title', 'RUGDesk')

@section('navbar')
    @parent

@endsection

@section('content')

    <p class="fs-4 border-bottom text-center" id="header"></p>

    <div class="col-3">
        <select id="overviewDepartment" class="form-select">
            @foreach ($departmentList as $department)
                <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="row mt-2">
        <div class="col col-lg-3 mt-3">
            <div class="card border-primary mt-3">
                <div class="card-body">
                    <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-line" style="color: blue"></i> Zgłoszeń łącznie</h5>
                    <span class="h2 font-weight-bold mb-0" id="allCard"></span>
                </div>
            </div>
            <div class="card border-warning mt-3">
                <div class="card-body">
                    <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa-solid fa-chart-area" style="color: orange"></i> W realizacji</h5>
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
        $('#header').text('Statystyki dla działu {{ $defaultDepartment}}');
    });

    $('#overviewDepartment').on('change', function() {
        department = $(this).val();
        $('#header').text('Statystyki dla działu ' + department);
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
            beforeSend: function() {
                $("#graphCanvas").hide();
                $("#loadingSpinner").show();
            },
            success: function(data){
                $("#loadingSpinner").hide();
                $("#graphCanvas").show();
                $('#allCard').text(data['all']);
                $('#allOpenCard').text(data['allOpen']);
                $('#allClosedCard').text(data['allClosed']);

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
                            label: 'Nowe',
                            data: data['new'],
                            fill: false,
                            borderColor: 'green',
                            tension: 0.1
                        },
                        {
                            label: 'Podjęte',
                            data: data['opened'],
                            fill: false,
                            borderColor: 'orange',
                            tension: 0.1
                        },
                        {
                            label: 'Zamknięte',
                            data: data['closed'],
                            fill: false,
                            borderColor: 'red',
                            tension: 0.1
                        },
                        ]
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: true}
                        }
                    }
                });
            }
        });
    }
    </script>
@endsection

@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-zones-tab" name="nav-tab" data-bs-toggle="tab" data-bs-target="#nav-zones" type="button" role="tab" aria-controls="nav-ticket" aria-selected="true">Obszary</button>
                <button class="nav-link" id="nav-positions-tab" name="nav-tab" data-bs-toggle="tab" data-bs-target="#nav-positions" type="button" role="tab" aria-controls="nav-note" aria-selected="false">Stanowiska</button>
                <button class="nav-link" id="nav-problems-tab" name="nav-tab" data-bs-toggle="tab" data-bs-target="#nav-problems" type="button" role="tab" aria-controls="nav-note" aria-selected="false">Problemy</button>
            </div>
        </nav>
        <button type="button" id="createNew" class="btn btn-success" style="float:right; margin: 0.7vw 1vw 0vw 0vw;" value="zone">Utwórz nowy obszar</button>
        <p id="header" class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">Zarządzaj obszarami</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-zones" role="tabpanel" aria-labelledby="nav-zones-tab">
                <table class="table table-striped table-hover responsive table align-middle">
                @if ($zones != null)
                    <thead>
                        <tr>
                            <td><b>Nazwa obszaru</b></td>
                            <td><b>Operacje</b></td>
                        </tr>
                    </thead>
                    <form method="post" action="{{ url('addZoneAction') }}">
                        @csrf
                        <tr id="newZoneForm" style="display: none">
                            <td><input class="form-control" type="text" name="zone_name"/></td>
                            <td><button class="btn btn-primary" name="create" id="create" value="create">Utwórz</button></td>
                        </tr>
                    </form>
                    @foreach($zones as $zone)
                        <tr>
                            <form method="post" action="{{ url('editZoneAction') }}">
                                @csrf
                                <td style="width: 80%"><input class="form-control" type="text" name="zone_name" value="{{ $zone->zone_name }}" id="{{ $zone->zoneID }}" disabled/></td>
                                <td style="width: 20%">
                                    <input type="button" class="btn btn-success" name="edit" id="edit-{{ $zone->zoneID }}" data-id="{{ $zone->zoneID }}" value="Edytuj"/>
                                    <button class="btn btn-success" name="save" id="save-{{ $zone->zoneID }}" value="{{ $zone->zoneID }}" style="display: none">Zapisz</button>
                                    <input type="button" class="btn btn-danger" name="delete" data-bs-toggle="modal" data-bs-target="#modal" data-type="zone" data-name="{{ $zone->zone_name }}" data-id="{{ $zone->zoneID }}" value="Usuń"/>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                @else
                    <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>
                @endif
                </table>
            </div>
            <div class="tab-pane fade" id="nav-positions" role="tabpanel" aria-labelledby="nav-positions-tab">
                <table class="table table-striped table-hover responsive table align-middle">
                    @if ($positions != null)
                        <thead>
                            <tr>
                                <td><b>Nazwa stanowiska</b></td>
                                <td><b>Obszary zawierające dane stanowiska</b></td>
                                <td><b>Operacje</b></td>
                            </tr>
                        </thead>
                        <form method="post" action="{{ url('addPositionAction') }}">
                            @csrf
                            <tr id="newPositionForm" style="display: none">
                                <td><input class="form-control" type="text" name="position_name"/></td>
                                <td><input class="form-control" type="text" name="zones_list"/></td>
                                <td><button class="btn btn-primary" name="create" id="create" value="create">Utwórz</button></td>
                            </tr>
                        </form>
                        @foreach($positions as $position)
                            <tr>
                                <td style="width: 20%"><input class="form-control" type="text" value="{{ $position->position_name }}" id="pos-{{ $position->positionID }}" disabled/></td>
                                <td style="width: 65%"><input class="form-control" type="text" value="{{ $position->zones_list }}" id="zonesList-{{ $position->positionID }}" disabled/></td>
                                <td style="width: 15%">
                                    <input type="button" class="btn btn-success" name="editForm" data-bs-toggle="modal" data-bs-target="#modal" data-id="{{ $position->positionID }}" data-zones="{{ $position->zones_list }}" data-name="{{ $position->position_name }}" data-type="position" value="Edytuj"/>
                                    <input type="button" class="btn btn-danger" name="delete" data-bs-toggle="modal" data-bs-target="#modal" data-type="position" data-name="{{ $position->position_name }}" data-id="{{ $position->positionID }}" value="Usuń"/>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>
                    @endif
                </table>
            </div>
            <div class="tab-pane fade" id="nav-problems" role="tabpanel" aria-labelledby="nav-problems-tab">
                <table class="table table-striped table-hover responsive table align-middle">
                @if ($problems != null)
                    <thead>
                        <tr>
                            <td><b>Kolejność wyświetlania</b></td>
                            <td><b>Nazwa problemu</b></td>
                            <td><b>Stanowiska zawierające dany problem</b></td>
                            <td><b>Działy zawierające dany problem</b></td>
                            <td><b>Operacje</b></td>
                        </tr>
                    </thead>
                    <form method="post" action="{{ url('addProblemAction') }}">
                        @csrf
                        <tr id="newProblemForm" style="display: none">
                            <td><input class="form-control" type="text" name="lp"/></td>
                            <td><input class="form-control" type="text" name="problem_name"/></td>
                            <td><input class="form-control" type="text" name="positions_list"/></td>
                            <td><input class="form-control" type="text" name="departments_list"/></td>
                            <td><button class="btn btn-primary" name="create" id="create" value="create">Utwórz</button></td>
                        </tr>
                    </form>
                    @foreach($problems as $problem)
                        <tr>
                            <td style="width: 5%"><input class="form-control" type="text" value="{{ $problem->lp }}" id="{{ $problem->problemID }}" disabled/></td>
                            <td style="width: 15%"><input class="form-control" type="text" value="{{ $problem->problem_name }}" id="name-{{ $problem->problemID }}" disabled/></td>
                            <td style="width: 45%"><input class="form-control" type="text" value="{{ $problem->positions_list }}" id="list-{{ $problem->problemID }}" disabled/></td>
                            <td style="width: 20%"><input class="form-control" type="text" value="{{ $problem->departments_list }}" id="department" disabled/></td>
                            <td style="width: 15%">
                                <input type="button" class="btn btn-success" name="editForm" data-bs-toggle="modal" data-bs-target="#modal" data-id="{{ $problem->problemID }}" data-lp="{{ $problem->lp }}" data-department="{{ $problem->departments_list }}" data-positions="{{ $problem->positions_list }}" data-name="{{ $problem->problem_name }}" data-type="problem" value="Edytuj"/>
                                <input type="button" class="btn btn-danger" name="delete" data-bs-toggle="modal" data-bs-target="#modal" data-type="problem" data-name="{{ $problem->position_name }}" data-id="{{ $problem->problemID }}" value="Usuń"/>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>
                @endif
                </table>
            </div>

            <!-- Okienko z potwierdzeniem -->
            <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <form id="modalForm" method="post" action="">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p id="text"><!-- Tekst ze skryptu JS --></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                <button type="Submit" id="confirmDelete" name="confirmDelete" class="btn btn-danger">Potwierdź</button>
                                <button type="Submit" id="confirmEdit" name="confirmEdit" class="btn btn-success">Zapisz</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
        $("[name=delete]").click(function() {
            $('#confirmEdit').hide();
            $('#confirmDelete').show();
            let buttonDelete = $(this).attr('data-name');
            let ID = $(this).attr('data-id');
            document.getElementById("confirmDelete").value = ID;

            switch ($(this).attr('data-type')){
                case ('zone'):
                    $('#modalForm').attr('action', "{{ url('deleteZoneAction') }}");
                    $('#modalLabel').text('Usuń obszar');
                    $('#text').text("Czy na pewno chcesz usunąć obszar " + buttonDelete + "?");
                    break;
                case ('position'):
                    $('#modalForm').attr('action', "{{ url('deletePositionAction') }}");
                    $('#modalLabel').text('Usuń stanowisko');
                    $('#text').text("Czy na pewno chcesz usunąć stanowisko " + buttonDelete + "?");
                    break;
                case ('problem'):
                    $('#modalForm').attr('action', "{{ url('deleteProblemAction') }}");
                    $('#modalLabel').text('Usuń problem');
                    $('#text').text("Czy na pewno chcesz usunąć problem " + buttonDelete + "?");
                    break;
            }
        });

        $("[name=editForm]").click(function() {
            $('#confirmDelete').hide();
            $('#confirmEdit').show();
            let name = $(this).attr('data-name');
            let lp = $(this).attr('data-lp');
            let department = $(this).attr('data-department');
            document.getElementById("confirmEdit").value = $(this).attr('data-id');

            switch ($(this).attr('data-type')){
                case ('position'):
                    $('#modalForm').attr('action', "{{ url('editPositionAction') }}");
                    $('#modalLabel').text('Edytuj stanowisko');
                    $('#text').text('');
                    $('#text').append('<input class="form-control" type="text" name="position_name" value="' + name + '""/><br/>')

                    let zoneList = $(this).attr('data-zones');
                    $.ajax({
                        type: "GET",
                        url: "formEditor/ajax/zones",
                        dataType: 'json',
                        success: function(zones){
                            $.each(zones, function(key, value) {
                                if (~zoneList.indexOf(value['zone_name']))
                                    input = '<input type="checkbox" class="form-check-input" name="' + value['zone_name'] + '" checked>';
                                else
                                    input = '<input type="checkbox" class="form-check-input" name="' + value['zone_name'] + '">';
                                $('#text').append('<tr> \
                                    <td> \
                                        <h5>' + input + ' \
                                            <label class="form-check-label" for="' + value['zone_name'] + '">' + value['zone_name'] + '</label> \
                                        </h5> \
                                    </td> \
                                </tr>');
                            });
                        }
                    });

                    break;
                case ('problem'):
                    $('#modalForm').attr('action', "{{ url('editProblemAction') }}");
                    $('#modalLabel').text('Edytuj problem');
                    $('#text').text('');
                    $('#text').append('<label for="lp" class="form-label">Kolejność wyświetlania</label><input class="form-control" type="text" name="lp" value="' + lp + '""/><br/>');
                    $('#text').append('<label for="problem_name" class="form-label">Problem</label><input class="form-control" type="text" name="problem_name" value="' + name + '""/><br/>');
                    $('#text').append('<label for="departments_list" class="form-label">Dział</label><select class="form-select" id="departmentSelect" name="departments_list" value="' + department + '""/>');
                    $('#text').append('<h4>Stanowiska:</h4>');

                    let positionList = $(this).attr('data-positions');
                    $.ajax({
                        type: "GET",
                        url: "formEditor/ajax/positions",
                        dataType: 'json',
                        success: function(positions){
                            $.each(positions, function(key, value) {
                                if (~positionList.indexOf(value['position_name']))
                                    input = '<input type="checkbox" class="form-check-input" name="' + value['position_name'] + '" checked>';
                                else
                                    input = '<input type="checkbox" class="form-check-input" name="' + value['position_name'] + '">';
                                $('#text').append('\
                                    <div class="form-group"> \
                                        <p>' + input + ' \
                                            <label class="form-check-label" for="exampleCheck1">' + value['position_name'] + '</label> \
                                        </p> \
                                    </div> \
                                ');
                            });
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: 'formEditor/ajax/departments',
                        dataType: "json",
                        success:function(departments) {
                            $('#departmentSelect').empty();
                            $.each(departments, function(key, value) {
                                if (department == value['department_name']){
                                    $('#departmentSelect').append('<option value="'+ value['department_name'] +'" selected>'+ value['department_name'] +'</option>');
                                }
                                $('#departmentSelect').append('<option value="'+ value['department_name'] +'">'+ value['department_name'] +'</option>');
                            });
                        }
                    });

                    break;
            }
        });

        let editButton = [];
        $("[name=edit]").click(function() {
            if (editButton[0] != null){
                $('#' + editButton[0]).prop("disabled", true);
                $('#list-' + editButton[0]).prop("disabled", true);
                $('#save-' + editButton[0]).hide();
                $('#edit-' + editButton[0]).show();
                editButton.shift();
            }

            editButton.push($(this).attr('data-id'));
            $('#' + editButton[0]).removeAttr('disabled');
            $('#list-' + editButton[0]).removeAttr('disabled');
            $(this).hide();
            $('#save-' + editButton[0]).show();
        });

        $("#createNew").click(function() {
            $("#createNew").val() == 'zone' ? $('#newZoneForm').toggle(300) : null;
            $("#createNew").val() == 'position' ? $('#newPositionForm').toggle(300) : null;
            $("#createNew").val() == 'problem' ? $('#newProblemForm').toggle(300) : null;
        });

        $("[name=nav-tab]").click(function() {
            if ($(this).attr('id') == 'nav-zones-tab'){
                $('#header').text('Zarządzaj obszarami');
                $('#createNew').text('Utwórz nowy obszar');
                $('#createNew').val('zone');
            }
            else if ($(this).attr('id') == 'nav-positions-tab'){
                $('#header').text('Zarządzaj stanowiskami');
                $('#createNew').text('Utwórz nowe stanowisko');
                $('#createNew').val('position');
            }
            else {
                $('#header').text('Zarządzaj problemami');
                $('#createNew').text('Utwórz nowy problem');
                $('#createNew').val('problem');
            }
        });
    </script>
@endsection

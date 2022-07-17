@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-zones-tab" name="nav-tab" data-bs-toggle="tab" data-bs-target="#nav-zones" type="button" role="tab" aria-controls="nav-zones" aria-selected="true">{{ __('dashboard_editor.zones') }}</button>
        <button class="nav-link" id="nav-positions-tab" name="nav-tab" data-bs-toggle="tab" data-bs-target="#nav-positions" type="button" role="tab" aria-controls="nav-positions" aria-selected="false">{{ __('dashboard_editor.positions') }}</button>
        <button class="nav-link" id="nav-problems-tab" name="nav-tab" data-bs-toggle="tab" data-bs-target="#nav-problems" type="button" role="tab" aria-controls="nav-problems" aria-selected="false">{{ __('dashboard_editor.problems') }}</button>
      </div>
    </nav>
    <button type="button" id="createNew" class="btn btn-success" style="float:right; margin: 0.7vw 1vw 0vw 0vw;" value="zone">{{ __('dashboard_editor.create_zone') }}</button>
    <p id="header" class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">{{ __('dashboard_editor.manage_zones') }}</p>
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
                <td><b>{{ __('dashboard_editor.zone_name') }}</b></td>
                <td><b>{{ __('dashboard_editor.operations') }}</b></td>
              </tr>
            </thead>
            <form method="post" action="{{ url('addZoneAction') }}">
              @csrf
              <tr id="newZoneForm" style="display: none">
                <td><input class="form-control" type="text" name="zone_name"/></td>
                <td><button class="btn btn-primary" name="create" id="create" value="create">{{ __('dashboard_editor.confirm_create') }}</button></td>
              </tr>
            </form>
            @foreach($zones as $zone)
              <tr>
                <form method="post" action="{{ url('editZoneAction') }}">
                  @csrf
                  <td style="width: 80%"><input class="form-control" type="text" name="zone_name" value="{{ $zone->zone_name }}" id="{{ $zone->zoneID }}" disabled/></td>
                  <td style="width: 20%">
                    <input type="button" class="btn btn-success" name="edit" id="edit-{{ $zone->zoneID }}" data-id="{{ $zone->zoneID }}" value="{{ __('dashboard_editor.edit') }}"/>
                    <button class="btn btn-success" name="save" id="save-{{ $zone->zoneID }}" value="{{ $zone->zoneID }}" style="display: none">{{ __('dashboard_editor.save_changes') }}</button>
                    <input type="button" class="btn btn-danger" name="delete" data-bs-toggle="modal" data-bs-target="#modal" data-type="zone" data-name="{{ $zone->zone_name }}" data-id="{{ $zone->zoneID }}" value="{{ __('dashboard_editor.delete') }}"/>
                  </td>
                </form>
              </tr>
            @endforeach
          @else
            <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">{{ __('dashboard_editor.no_zones_found') }}</p>
          @endif
        </table>
      </div>
      <div class="tab-pane fade" id="nav-positions" role="tabpanel" aria-labelledby="nav-positions-tab">
        <table class="table table-striped table-hover responsive table align-middle">
          @if ($positions != null)
            <thead>
              <tr>
                <td><b>{{ __('dashboard_editor.position_name') }}</b></td>
                <td><b>{{ __('dashboard_editor.positions_in_zones') }}</b></td>
                <td><b>{{ __('dashboard_editor.operations') }}</b></td>
              </tr>
            </thead>
            @foreach($positions as $position)
              <tr>
                <td style="width: 20%"><input class="form-control" type="text" value="{{ $position->position_name }}" id="pos-{{ $position->positionID }}" disabled/></td>
                <td style="width: 65%"><input class="form-control" type="text" value="{{ $position->zones_list }}" id="zonesList-{{ $position->positionID }}" disabled/></td>
                <td style="width: 15%">
                  <input type="button" class="btn btn-success" name="editForm" data-bs-toggle="modal" data-bs-target="#modal" data-id="{{ $position->positionID }}" data-zones="{{ $position->zones_list }}" data-name="{{ $position->position_name }}" data-type="position" value="{{ __('dashboard_editor.edit') }}"/>
                  <input type="button" class="btn btn-danger" name="delete" data-bs-toggle="modal" data-bs-target="#modal" data-type="position" data-name="{{ $position->position_name }}" data-id="{{ $position->positionID }}" value="{{ __('dashboard_editor.delete') }}"/>
                </td>
              </tr>
            @endforeach
          @else
            <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">{{ __('dashboard_editor.no_positions_found') }}</p>
          @endif
        </table>
      </div>
      <div class="tab-pane fade" id="nav-problems" role="tabpanel" aria-labelledby="nav-problems-tab">
        <table class="table table-striped table-hover responsive table align-middle">
          @if ($problems != null)
            <thead>
              <tr>
                <td><b>{{ __('dashboard_editor.display_order') }}</b></td>
                <td><b>{{ __('dashboard_editor.problem_name') }}</b></td>
                <td><b>{{ __('dashboard_editor.problems_in_positions') }}</b></td>
                <td><b>{{ __('dashboard_editor.problems_in_departments') }}</b></td>
                <td><b>{{ __('dashboard_editor.operations') }}</b></td>
              </tr>
            </thead>
            @foreach($problems as $problem)
              <tr>
                <td style="width: 5%"><input class="form-control" type="text" value="{{ $problem->lp }}" id="{{ $problem->problemID }}" disabled/></td>
                <td style="width: 15%"><input class="form-control" type="text" value="{{ $problem->problem_name }}" id="name-{{ $problem->problemID }}" disabled/></td>
                <td style="width: 45%"><input class="form-control" type="text" value="{{ $problem->positions_list }}" id="list-{{ $problem->problemID }}" disabled/></td>
                <td style="width: 20%"><input class="form-control" type="text" value="{{ $problem->departments_list }}" id="department" disabled/></td>
                <td style="width: 15%">
                  <input type="button" class="btn btn-success" name="editForm" data-bs-toggle="modal" data-bs-target="#modal" data-id="{{ $problem->problemID }}" data-lp="{{ $problem->lp }}" data-department="{{ $problem->departments_list }}" data-positions="{{ $problem->positions_list }}" data-name="{{ $problem->problem_name }}" data-type="problem" value="{{ __('dashboard_editor.edit') }}"/>
                  <input type="button" class="btn btn-danger" name="delete" data-bs-toggle="modal" data-bs-target="#modal" data-type="problem" data-name="{{ $problem->problem_name }}" data-id="{{ $problem->problemID }}" value="{{ __('dashboard_editor.delete') }}"/>
                </td>
              </tr>
            @endforeach
          @else
            <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">{{ __('dashboard_editor.no_problems_found') }}</p>
          @endif
        </table>
      </div>

      <!-- Modal confirmation window -->
      <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <form id="modalForm" method="post" action="">
          @csrf
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"><!-- JS inserted modal title --></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p id="text"><!-- JS inserted text --></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard_editor.cancel') }}</button>
                <button type="Submit" id="confirmDelete" name="confirmDelete" class="btn btn-danger">{{ __('dashboard_editor.confirm_delete') }}</button>
                <button type="Submit" id="confirmEdit" name="confirmEdit" class="btn btn-success">{{ __('dashboard_editor.save_changes') }}</button>
                <button type="Submit" id="confirmCreate" name="confirmCreate" class="btn btn-primary">{{ __('dashboard_editor.confirm_create') }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
    <script>
        /**
         * Delete buttons trigger function.
         */
        $("[name=delete]").click(function() {
            $('#confirmEdit').hide();
            $('#confirmCreate').hide();
            $('#confirmDelete').show();
            let buttonDelete = $(this).attr('data-name');
            let ID = $(this).attr('data-id');
            document.getElementById("confirmDelete").value = ID;

            switch ($(this).attr('data-type')){
                case ('zone'):
                    $('#modalForm').attr('action', "{{ url('deleteZoneAction') }}");
                    $('#modalLabel').text("{{ __('dashboard_editor.delete_zone_modal_label') }}");
                    $('#text').text("{{ __('dashboard_editor.delete_zone_modal_text') }} " + buttonDelete + "?");
                    break;
                case ('position'):
                    $('#modalForm').attr('action', "{{ url('deletePositionAction') }}");
                    $('#modalLabel').text("{{ __('dashboard_editor.delete_position_modal_label') }}");
                    $('#text').text("{{ __('dashboard_editor.delete_position_modal_text') }} " + buttonDelete + "?");
                    break;
                case ('problem'):
                    $('#modalForm').attr('action', "{{ url('deleteProblemAction') }}");
                    $('#modalLabel').text("{{ __('dashboard_editor.delete_problem_modal_label') }}");
                    $('#text').text("{{ __('dashboard_editor.delete_problem_modal_text') }} " + buttonDelete + "?");
                    break;
            }
        });

        /**
         * Edit buttons trigger function. Runs ajax queries depending on the selected nav tab.
         */
        $("[name=editForm]").click(function() {
            $('#confirmDelete').hide();
            $('#confirmCreate').hide();
            $('#confirmEdit').show();
            let name = $(this).attr('data-name');
            let lp = $(this).attr('data-lp');
            let department = $(this).attr('data-department');
            document.getElementById("confirmEdit").value = $(this).attr('data-id');

            switch ($(this).attr('data-type')){
                /**
                 * Action triggered when Edit button is clicked and positions tab is currently active.
                 * Ajax query grabs all available zones and appends them to modal form as checkbox options for selection.
                 */
                case ('position'):
                    $('#modalForm').attr('action', "{{ url('editPositionAction') }}");
                    $('#modalLabel').text("{{ __('dashboard_editor.edit_position_modal_label') }}");
                    $('#text').text('');
                    $('#text').append('<input class="form-control" type="text" name="position_name" value="' + name + '""/><br/>');

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

                /**
                 * Action triggered when Edit button is clicked and problems tab is currently active.
                 * First ajax query grabs all available positions and appends them to modal form as checkbox options for selection.
                 * Second query inserts all departments in form of select options.
                 */
                case ('problem'):
                    $('#modalForm').attr('action', "{{ url('editProblemAction') }}");
                    $('#modalLabel').text("{{ __('dashboard_editor.edit_problem_modal_label') }}");
                    $('#text').text('');
                    $('#text').append('<label for="lp" class="form-label">{{ __("dashboard_editor.display_order") }}</label><input class="form-control" type="text" name="lp" value="' + lp + '""/><br/>');
                    $('#text').append('<label for="problem_name" class="form-label">{{ __("dashboard_editor.problem_name") }}</label><input class="form-control" type="text" name="problem_name" value="' + name + '""/><br/>');
                    $('#text').append('<label for="departments_list" class="form-label">{{ __("dashboard_editor.department_name") }}</label><select class="form-select" id="departmentSelect" name="departments_list" value="' + department + '""/>');
                    $('#text').append('<br/><h4>{{ __("dashboard_editor.positions") }}:</h4>');

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
                                            <label class="form-check-label" for="' + value['position_name'] + '">' + value['position_name'] + '</label> \
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

        /**
         * Edit button trigger for zones nav tab. When Edit is clicked, zone name input field is enabled for editing and
         * can be saved with Save button. When another Edit button is clicked the previously unlocked row is disabled.
         */
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

        /**
         * Function responsible for changing text values depedning on selected nav tab.
         */
        $("[name=nav-tab]").click(function() {
            if ($(this).attr('id') == 'nav-zones-tab'){
                $('#header').text("{{ __('dashboard_editor.manage_zones') }}");
                $('#createNew').text("{{ __('dashboard_editor.create_zone') }}");
                $('#createNew').val('zone');
            }
            else if ($(this).attr('id') == 'nav-positions-tab'){
                $('#header').text("{{ __('dashboard_editor.manage_positions') }}");
                $('#createNew').text("{{ __('dashboard_editor.create_position') }}");
                $('#createNew').val('position');
            }
            else {
                $('#header').text("{{ __('dashboard_editor.manage_problems') }}");
                $('#createNew').text("{{ __('dashboard_editor.create_problem') }}");
                $('#createNew').val('problem');
            }
        });

        /**
         * Different behaviours for "create new" button depending on selected nav tab.
         */
        $("#createNew").click(function() {
            $("#createNew").val() == 'zone' ? $('#newZoneForm').toggle(300) : null;
            $("#createNew").val() == 'position' ? newPositionForm() : null;
            $("#createNew").val() == 'problem' ? newProblemForm() : null;
        });

        /**
         * Function for new position modal window trigger.
         */
        function newPositionForm(){
            $('#modal').modal('show');
            $('#confirmCreate').show();
            $('#confirmEdit').hide();
            $('#confirmDelete').hide();
            $('#modalForm').attr('action', "{{ url('addPositionAction') }}");
            $('#modalLabel').text("{{ __('dashboard_editor.create_position') }}");
            $('#text').text('');
            $('#text').append('<label for="position_name" class="form-label">{{ __("dashboard_editor.position_name") }}</label><input class="form-control" type="text" name="position_name" required/><br/>');
            $('#text').append('<h4>{{ __("dashboard_editor.zones") }}:</h4>');

            $.ajax({
                type: "GET",
                url: "formEditor/ajax/zones",
                dataType: 'json',
                success: function(zones){
                    $.each(zones, function(key, value) {
                        input = '<input type="checkbox" id="' + value['zone_name'] + '" class="form-check-input" name="' + value['zone_name'] + '">';
                        $('#text').append('<tr> \
                            <td> \
                                <h4>' + input + ' \
                                    <label class="form-check-label" for="' + value['zone_name'] + '">' + value['zone_name'] + '</label> \
                                </h4> \
                            </td> \
                        </tr>');
                    });
                }
            });
        }

        /**
         * Function for new problem modal window trigger.
         */
        function newProblemForm(){
            $('#modal').modal('show');
            $('#confirmCreate').show();
            $('#confirmEdit').hide();
            $('#confirmDelete').hide();
            $('#modalForm').attr('action', "{{ url('addProblemAction') }}");
            $('#modalLabel').text("{{ __('dashboard_editor.create_problem') }}");
            $('#text').text('');
            $('#text').append('<label for="lp" class="form-label">{{ __("dashboard_editor.display_order") }}</label><input class="form-control" type="number" name="lp" required/><br/>');
            $('#text').append('<label for="problem_name" class="form-label">{{ __("dashboard_editor.problem_name") }}</label><input class="form-control" type="text" name="problem_name" required/><br/>');
            $('#text').append('<label for="departments_list" class="form-label">{{ __("dashboard_editor.department_name") }}</label><select class="form-select" id="departmentSelect" name="departments_list" required/>');
            $('#text').append('<br/><h4>{{ __("dashboard_editor.positions") }}:</h4>');

            $.ajax({
                type: "GET",
                url: "formEditor/ajax/positions",
                dataType: 'json',
                success: function(positions){
                    $.each(positions, function(key, value) {
                        input = '<input type="checkbox" id="' + value['position_name'] + '" class="form-check-input" name="' + value['position_name'] + '">';
                        $('#text').append('\
                            <div class="form-group"> \
                                <h5>' + input + ' \
                                    <label class="form-check-label" for="' + value['position_name'] + '">' + value['position_name'] + '</label> \
                                </h5> \
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
                        $('#departmentSelect').append('<option value="'+ value['department_name'] +'">'+ value['department_name'] +'</option>');
                    });
                }
            });
        }
    </script>
@endsection

@extends('dashboard/dashboard_template')

@section('title', 'RUGDesk')

@section('sidebar')
  @parent

@endsection

@section('content')

  @php
    $date_closed  = new DateTime($ticket->date_closed);
    $date_closed->add(new DateInterval('P2D'));
    $date_now = new DateTime('NOW');
  @endphp

  <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
    <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_tickets.ticket_header') }}</p>
    @if (session('message'))
      <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if ($ticket->ticket_status == -1)
      <div class="alert alert-info">
        <h4>{{ __('dashboard_tickets.awaiting_header') }}</h4>
        - {!! __('dashboard_tickets.awaiting_accept_desc') !!} <br/>
        - {!! __('dashboard_tickets.awaiting_reject_desc') !!} <br/>
        - {!! __('dashboard_tickets.awaiting_take_desc') !!} <br/>
      </div>
    @endif
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-ticket-tab" data-bs-toggle="tab" data-bs-target="#nav-ticket" type="button" role="tab" aria-controls="nav-ticket" aria-selected="true">{{ __('dashboard_tickets.navtab_ticket') }}</button>
        <button class="nav-link" id="nav-note-tab" data-bs-toggle="tab" data-bs-target="#nav-note" type="button" role="tab" aria-controls="nav-note" aria-selected="false">{{ __('dashboard_tickets.navtab_add_note') }}</button>
        <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history" type="button" role="tab" aria-controls="nav-note" aria-selected="false">{{ __('dashboard_tickets.navtab_ticket_history') }}</button>
      </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="nav-ticket" role="tabpanel" aria-labelledby="nav-ticket-tab">
        <form method="post" action="{{ url('modifyTicketAction/'.$ticket->ticketID) }}">
          @csrf
          <div class="row" style="margin-top:1vw;">
            <div class="col">
              <span class="fs-5">{{ __('dashboard_tickets.table_date_created') }} {{ $ticket->date_created }}</span>
              <span class="fs-5" style="margin-left: 2vw">{{ __('dashboard_tickets.date_taken') }}
                @if ($ticket->date_opened == null)
                  --------
                @else
                  {{ $ticket->date_opened }}
                @endif
              </span>
              <span class="fs-5" style="margin-left: 2vw">{{ __('dashboard_tickets.table_date_closed') }}
                @if ($ticket->date_closed == null)
                  --------
                @else
                  {{ $ticket->date_closed }}
                @endif
              </span>
              <span class="fs-5" style="margin-left: 4.3vw;">{{ __('dashboard_tickets.table_status') }}
                @if ($ticket->ticket_status == -1)
                  <span class="badge rounded-pill bg-primary">{{ __('dashboard_tickets.filter_awaiting') }}</span>
                @elseif($ticket->ticket_status == 0)
                  <span class="badge rounded-pill bg-success">{{ __('dashboard_tickets.filter_new') }}</span>
                @elseif($ticket->ticket_status == 1)
                  <span class="badge rounded-pill bg-warning">{{ __('dashboard_tickets.filter_in_progress') }}</span>
                @elseif($date_now > $date_closed)
                  <span class="badge rounded-pill bg-danger">{{ __('dashboard_tickets.pill_closed_permamently') }}</span>
                @else
                  <span class="badge rounded-pill bg-danger">{{ __('dashboard_tickets.filter_closed') }}</span>
                @endif
              </span>
            </div>
          </div>
          <div class="row" style="margin-top:1vw;">
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.table_device') }}</label>
              <input type="text" class="form-control" value="{{ $ticket->device_name }}" disabled/>
            </div>
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.raised_by') }}</label>
              <input type="text" class="form-control" value="{{ $ticket->username }}" disabled/>
            </div>
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.table_zone') }}</label>
              <input type="text" class="form-control" value="{{ $ticket->zone }}" disabled/>
            </div>
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.table_position') }}</label>
              <input type="text" class="form-control" value="{{ $ticket->position }}" disabled/>
            </div>
          </div>
          <div class="row" style="margin-top:1vw;">
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.department') }}</label>
              <select id="departmentSelect" name="departmentSelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                @foreach ($departments as $department)
                  <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.table_problem') }}</label>
              <select id="problemSelect" name="problemSelect" class="form-select" required {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                @foreach ($problems as $problem)
                  <option value="{{ $problem->problem_name }}">{{ $problem->problem_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.priority') }}</label>
              <select id="prioritySelect" name="prioritySelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                <option value="0">{{ __('dashboard_tickets.priority_low') }}</option>
                <option value="2">{{ __('dashboard_tickets.priority_medium') }}</option>
                <option value="4">{{ __('dashboard_tickets.priority_high') }}</option>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:1vw;">
            @if ($ticket->ticket_status != 0 && $ticket->ticket_status != -1)
              <hr>
              <div class="col-4">
                <label class="form-label">{{ __('dashboard_tickets.table_owner') }}</label>
                <select id="ownerSelect" name="ownerSelect" class="form-select" required {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                  @foreach ($staffMembers as $member)
                    @if ($member->login != 'root')
                      <option value="{{ $member->name }}">{{ $member->name }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="col-4">
                <label class="form-label">{{ __('dashboard_tickets.external_ticket') }}</label>
                @if ($ticket->external_ticketID != null)
                  <input type="checkbox" id="isExternal" class="form-check-input" checked {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                  <input type="text" id="external_ticketID" name="external_ticketID" class="form-control" value="{{ $ticket->external_ticketID }}"/>
                @else
                  <input type="checkbox" id="isExternal" class="form-check-input" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                  <input type="text" id="external_ticketID" name="external_ticketID" class="form-control" value="" disabled/>
                @endif
              </div>
              <div class="col-4">
                <label class="form-label">{{ __('dashboard_tickets.time_spent_on') }}</label>
                <input type="text" id="time_spent" class="form-control" value="{{ date('H:i', strtotime($ticket->time_spent)) }}" disabled/>
              </div>
            @endif
          </div>

          <!-- Button group -->
          <div class="row" style="margin-top:1vw;">
            <div class="col">
              @if ($ticket->ticket_status == -1)
                <input type="button" class="btn btn-success" id="accept" data-bs-toggle="modal" data-bs-target="#modal" value="{{ __('dashboard_tickets.accept_ticket') }}" data-id="acceptTicket"/>
                <input type="button" class="btn btn-danger" id="close" data-bs-toggle="modal" data-bs-target="#modal" value="{{ __('dashboard_tickets.reject_ticket') }}" data-id="rejectTicket"/>
                <input name="takeTicket" class="btn btn-warning" type="Submit" value="{{ __('dashboard_tickets.take_ticket') }}"/>
              @elseif ($ticket->ticket_status == 0)
                <input name="takeTicket" class="btn btn-warning" type="Submit" value="{{ __('dashboard_tickets.take_ticket') }}"/>
              @elseif ($ticket->ticket_status == 1)
                <input name="editTicket" class="btn btn-success" type="Submit" value="{{ __('dashboard_tickets.save_button') }}"/>
                <input type="button" class="btn btn-danger" id="close" data-bs-toggle="modal" data-bs-target="#modal" value="{{ __('dashboard_tickets.close_ticket') }}" data-id="closeTicket"/>
                <span class="btn-group" style="float: right">
                  <button name="timerAction" type="button" class="btn btn-outline-primary mx-1" value="5">+ 5 {{ __('dashboard_tickets.timer_minutes_button') }}</button>
                  <button name="timerAction" type="button" class="btn btn-outline-secondary" value="15">+ 15 {{ __('dashboard_tickets.timer_minutes_button') }}</button>
                  <button name="timerAction" type="button" class="btn btn-outline-dark ms-1" value="30">+ 30 {{ __('dashboard_tickets.timer_minutes_button') }}</button>
                </span>
              @elseif ($date_now < $date_closed && $ticket->target_department == null)
                <input name="reopenTicket" id="reopenTicket" class="btn btn-primary" type="Submit" value="{{ __('dashboard_tickets.reopen_ticket') }}"/>
              @endif
            </div>
          </div>

          <!-- Confirmation window -->
          <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel"></h5>
                  <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <label for="ticketType" class="form-label">{{ __('dashboard_tickets.ticket_type') }}</label>
                  <select id="ticketType" name="ticketType" class="form-select mb-3">
                    <option value="valid">{{ __('dashboard_tickets.ticket_type_valid') }}</option>
                    <option value="invalid">{{ __('dashboard_tickets.ticket_type_invalid') }}</option>
                  </select>
                  <p id="modalContent"></p>
                  <textarea class="form-control" id="closingNotes" name="closingNotes" maxlength="250"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">{{ __('dashboard_tickets.modal_cancel') }}</button>
                  <input type="Submit" id="confirmClose" name="" value="{{ __('dashboard_tickets.modal_confirm') }}" class="btn btn-danger"/>
                </div>
              </div>
            </div>
          </div>

        </form>
        <div class="col">
          <p class="fs-4 border-bottom">{{ __('dashboard_tickets.attachments') }}</p>
          @if ($ticket->ticket_status != 2)
            <div class="form-group top-margin">
              <label class="form-label">{{ __('dropzone.label') }}</label><br/>
              <div class="dropzone" id="myDropzone">
                <div class="data-dz-message"><span></span></div>
              </div>
            </div>
          @endif
          @if ($attachments->count() > 0)
            <div class="row align-items-end">
              @foreach ($attachments as $attachment)
                @switch ($attachmentsDisplay[$attachment->file_name])
                  @case ('image')
                    <div class="col-2 text-center">
                      <a href="{{ url('storage/'.$attachment->file_path.$attachment->file_name) }}" data-lightbox="image" data-title="{{ $attachment->file_name }}">
                        <img src="{{ url('storage/'.$attachment->file_path.$attachment->file_name) }}" class="img-fluid" style="min-width: 100%; min-height: 100%; width: auto; height: auto;"/>
                      </a>
                    </div>
                    @break
                  @case ('download')
                    <div class="col-2 text-center">
                      <img src="{{ asset('img/download-icon.png') }}" class="img-fluid" style="max-width: 50%; max-height: 50%; width: auto; height: auto;"/>
                      <label for="download" class="form-label">{{ $attachment->file_name }}</label><br/>
                      <a href="{{ url('storage/'.$attachment->file_path.$attachment->file_name) }}" download="{{ $attachment->file_name }}">
                        <button class="btn btn-primary" name="download"><i class="fa fa-download"></i> {{ __('dashboard_tickets.download_attachment') }}</button>
                      </a>
                    </div>
                    @break
                  @default
                    @break
                @endswitch
              @endforeach
            </div>
          @else
            {{ __('dashboard_tickets.no_attachments') }}
          @endif
        </div>
        <div class="row" style="margin-top:1vw;">
          <div class="col">
            <p class="fs-4 border-bottom">{{ __('dashboard_tickets.ticket_message') }}</p>
            <span class="lead" style="overflow-wrap: break-word;">
              @if($ticket->ticket_message != null)
                {{ $ticket->ticket_message }}
              @else
                {{ __('dashboard_tickets.no_message') }}
              @endif
            </span>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="nav-note" role="tabpanel" aria-labelledby="nav-note-tab">
        <div class="row" style="margin-top:1vw;">
          <form method="post" action="{{ url('addNote/'.$ticket->ticketID) }}">
            @csrf
            <div class="col">
              <label class="form-label">{{ __('dashboard_tickets.add_note_label') }}</label>
              <textarea class="form-control" name="noteContents" maxlength="250"></textarea><br/>
              <input name="addNote" class="btn btn-primary" type="Submit" value="{{ __('dashboard_tickets.add_note_button') }}"/>
            </div>
          </form>
        </div>
      </div>
      <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab">
        <div class="row" style="margin-top:1vw;">
          <div class="col">
            @foreach ($history as $data)
              <div class="col rounded shadow" style="background: white; margin-top:1vw; padding: 1vw 1vw 0.5vw 1vw;">
                <p class="fs-5 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_tickets.edit_header', ['username' => $data['username'], 'date' => $data['date_modified']]) }}</p>
                <p class="lead" style="overflow-wrap: break-word;">{{ $data->contents }}</p>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  @foreach ($notes as $note)
    <div class="col rounded shadow" style="background: white; margin-top:1vw; padding: 1vw 1vw 0.5vw 1vw;">
      <p class="fs-5 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_tickets.note', ['created_at' => $note->created_at, 'username' => $note->username]) }}</p>
      <p class="lead" style="overflow-wrap: break-word;">{{ $note->contents }}</p>
    </div>
  @endforeach

    <script>
        /**
         * Set select fields with values from actual ticket.
         */
        $('#prioritySelect').val({{ $ticket->priority }});
        $('#problemSelect').val($('<div />').html('{{ $ticket->problem }}').text());
		$('#ownerSelect').val($('<div />').html('{{ $ticket->owner }}').text());

        /**
         * Part of ticket acceptance logic. If ticket must be accepted by differend department than user's
         * then it sets department as targeted department. If not, the default department field is used.
         */
        var targetDepartment = '{{ $ticket->target_department }}';
        if (targetDepartment != '' && targetDepartment != null && targetDepartment != undefined) {
            $('#departmentSelect').val('{{ $ticket->target_department }}');
        }
        else{
            $('#departmentSelect').val('{{ $ticket->department }}');
        }

        /**
         * Get problem list and staff members for chosen department.
         */
        $(document).ready(function() {
            $('#departmentSelect').on('change', function() {
                var department = $(this).val();
                if(department) {
                    $.ajax({
                        url: 'ajax/'+department,
                        type: "GET",
                        dataType: "json",
                        success:function(ajaxData) {

                            $('#problemSelect').empty();
                            $('#ownerSelect').empty();

                            for(var i in ajaxData['problems']){
                                $('#problemSelect').append('<option value="'+ ajaxData['problems'][i]['problem_name'] +'">'+ ajaxData['problems'][i]['problem_name'] +'</option>');
                            }
                            for(var i in ajaxData['members']){
                                $('#ownerSelect').append('<option value="'+ ajaxData['members'][i]['name'] +'">'+ ajaxData['members'][i]['name'] +'</option>');
                            }

                        }
                    });
                }
            });
        });

        /**
         * Ajax call for timerAction buttons. When one of the buttons is clicked, the time spent on ticket is increased
         * by picked value.
         */
        $("button[name='timerAction']").click(function() {
            var timer = $(this).val();
            var id = "{{ $ticket->ticketID }}";
            $.ajax({
                url: id+'/ajax/'+timer,
                type: "POST",
                dataType: "json",
                data: { "_token": "{{ csrf_token() }}"},
                success:function(time_spent) {
                    time_spent = new Date(time_spent['time_spent']['date']);
                    time_spent =  ('0' + time_spent.getHours()).slice(-2) + ":" + ('0' + time_spent.getMinutes()).slice(-2);
                    $('#time_spent').val(time_spent);
                }
            });
        });

        /**
         * Workaround for ownerSelect. When ticket is accepted/closed empty field was displayed
         */
        if ($('#ownerSelect').val() == null){
            $('#ownerSelect').append('<option value="'+ '{{ $ticket->owner }}' +'">'+ '{{ $ticket->owner }}' +'</option>');
            $('#ownerSelect').val($('<div />').html('{{ $ticket->owner }}').text());
        }
        else{
            $('#ownerSelect').val($('<div />').html('{{ $ticket->owner }}').text());
        }

        /**
         * External ticket input field toggler.
         */
        $('#isExternal').click(function() {
            if ($('#isExternal').is(':checked')){
                $('#external_ticketID').removeAttr('disabled', 'disabled');
            }
            else if (!$('#isExternal').is(':checked')){
                $('#external_ticketID').prop('disabled', 'disabled');

            }
        });

        /**
         * Modal window for different ticket actions.
         */
        $("#close, #accept").click(function() {
            var type = $(this).attr('data-id');
            switch (type) {
                case 'rejectTicket':
                    $('#confirmClose').attr('name', 'rejectTicket');
                    $('#modalLabel').text('{{ __("dashboard_tickets.reject_ticket") }}');
                    $('#modalContent').text('{{ __("dashboard_tickets.close_ticket_note") }}');
                    $('#ticketType').prop('disabled', 'disabled');
                    $('#ticketType').val('invalid');
                    break;
                case 'closeTicket':
                    $('#confirmClose').attr('name', 'closeTicket');
                    $('#modalLabel').text('{{ __("dashboard_tickets.close_ticket") }}');
                    $('#modalContent').text('{{ __("dashboard_tickets.close_ticket_note") }}');
                    break;
                case 'acceptTicket':
                    $('#confirmClose').attr('name', 'acceptTicket');
                    $('#modalLabel').text('{{ __("dashboard_tickets.accept_ticket") }}');
                    $('#modalContent').text('{{ __("dashboard_tickets.accept_ticket_note") }}');
                    $('#ticketType').prop('disabled', 'disabled');
                    $('#ticketType').val('valid');
                    break;
            }

            $('#ticketType').prop('required', true)
            $("#closingNotes").prop('required', true);
        });

        /**
         * Remove closing notes field fill requirement on modal close.
         */
        $(document).on('hide.bs.modal','#modal', function () {
            $('#ticketType').prop('required', false)
            $("#closingNotes").prop('required', false);
        });

        /**
         * Check if ticket is closed. If true, start countdown timer.
         */
        if ("{{ $ticket->date_closed }}" && $('#reopenTicket').val() != null){
            countdown();
        }

        /**
         * Countdown logic for "Reopen ticket" button.
         */
        function countdown(){
            var countdownStart = new Date("{{ $ticket->date_closed }}").getTime() + 48 * 60 * 60 * 1000;

            var x = setInterval(function() {

                var now = new Date().getTime();

                var distance = countdownStart - now;

                var hours = Math.floor((distance % (1000 * 60 * 60 * 48)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("reopenTicket").value = "{{ __('dashboard_tickets.reopen_ticket') }} (" + ('0' + hours).slice(-2) + ":" + ('0' + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2) + ")";

                if (distance < 0) {
                    clearInterval(x);
                }
            }, 100);
        }

        /**
         * Dropzone options
         */
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone(".dropzone", {
            url: "{{ $ticket->ticketID }}/dropzoneUpload",
            autoProcessQueue: true,
            uploadMultiple: true,
            parallelUploads: 3,
            maxFiles: 3,
            maxFilesize: 5,
            dictDefaultMessage: '<img src="{{ asset('img/upload-icon.png') }}" class="img-fluid" style="max-width:25%"/><br/> {{ __("dropzone.drop_here") }}',
            dictFileTooBig: "{{ __('dropzone.file_too_big') }}",
            dictInvalidFileType: "{{ __('dropzone.invalid_file_type') }}",
            dictCancelUpload: "{{ __('dropzone.cancel_upload') }}",
            dictUploadCanceled: "{{ __('dropzone.upload_canceled') }}",
            dictRemoveFile: "{{ __('dropzone.remove_file') }}",
            dictMaxFilesExceeded: "{{ __('dropzone.max_files_exceeded') }}",
            //acceptedFiles: 'image/*',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            }
        });

    </script>
@endsection

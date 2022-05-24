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
        <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Szczegóły zgłoszenia</p>
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        @if ($ticket->ticket_status == -1)
            <div class="alert alert-info">
                <h4>Zgłoszenie oczekuje na zatwierdzenie.</h4>
                - <b>Zatwierdź zgłoszenie</b> - zgłoszenie trafi do docelowego działu podanego w polu <b>Dział obsługi</b>. </br>
                - <b>Odrzuć</b> - zgłoszenie zostanie zamknięte. </br>
                - <b>Podejmij zgłoszenie</b> - w wypadku, gdy zgłoszenie możesz rozwiązać sam.
            </div>
        @endif
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-ticket-tab" data-bs-toggle="tab" data-bs-target="#nav-ticket" type="button" role="tab" aria-controls="nav-ticket" aria-selected="true">Zgłoszenie</button>
                <button class="nav-link" id="nav-note-tab" data-bs-toggle="tab" data-bs-target="#nav-note" type="button" role="tab" aria-controls="nav-note" aria-selected="false">Dodaj notatkę</button>
                <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history" type="button" role="tab" aria-controls="nav-note" aria-selected="false">Historia zgłoszenia</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-ticket" role="tabpanel" aria-labelledby="nav-ticket-tab">
                <form method="post" action="{{ url('modifyTicketAction/'.$ticket->ticketID) }}">
                    @csrf
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <span class="fs-5">Data utworzenia {{ $ticket->date_created }}</span>
                            <span class="fs-5" style="margin-left: 2vw">Data podjęcia
                                    @if ($ticket->date_opened == null)
                                        --------
                                    @else
                                        {{ $ticket->date_opened }}
                                    @endif
                            </span>
                            <span class="fs-5" style="margin-left: 2vw">Data zamknięcia
                                    @if ($ticket->date_closed == null)
                                        --------
                                    @else
                                        {{ $ticket->date_closed }}
                                    @endif
                            </span>
                            <span class="fs-5" style="margin-left: 4.3vw;">Status
                                @if ($ticket->ticket_status == -1)
                                    <span class="badge rounded-pill bg-primary">Oczekujące</span>
                                @elseif($ticket->ticket_status == 0)
                                    <span class="badge rounded-pill bg-success">Nowe</span>
                                @elseif($ticket->ticket_status == 1)
                                    <span class="badge rounded-pill bg-warning">Podjęte</span>
                                @elseif($date_now > $date_closed)
                                    <span class="badge rounded-pill bg-danger">Zamknięte permamentnie</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Zamknięte</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <label class="form-label">Nazwa komputera</label>
                            <input type="text" class="form-control" value="{{ $ticket->device_name }}" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Zgłaszający</label>
                            <input type="text" class="form-control" value="{{ $ticket->username }}" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Obszar produkcji</label>
                            <input type="text" class="form-control" value="{{ $ticket->zone }}" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Stanowisko</label>
                            <input type="text" class="form-control" value="{{ $ticket->position }}" disabled/>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <label class="form-label">Dział obsługi</label>
                            <select id="departmentSelect" name="departmentSelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Problem</label>
                            <select id="problemSelect" name="problemSelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                @foreach ($problems as $problem)
                                    <option value="{{ $problem->problem_name }}">{{ $problem->problem_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Priorytet</label>
                            <select id="prioritySelect" name="prioritySelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                <option value="0">Powiadomienie</option>
                                <option value="2">Standardowy</option>
                                <option value="4">Krytyczy</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        @if ($ticket->ticket_status != 0 && $ticket->ticket_status != -1)
                            <hr>
                            <div class="col-4">
                                <label class="form-label">Osoba odpowiedzialna</label>
                                <select id="ownerSelect" name="ownerSelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                    @foreach ($staffMembers as $member)
                                        @if ($member->login != 'root')
                                            <option value="{{ $member->name }}">{{ $member->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Zgłoszenie zewnętrzne</label>
                                @if ($ticket->external_ticketID != null)
                                    <input type="checkbox" id="isExternal" class="form-check-input" checked {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                    <input type="text" id="external_ticketID" name="external_ticketID" class="form-control" value="{{ $ticket->external_ticketID }}"/>
                                @else
                                    <input type="checkbox" id="isExternal" class="form-check-input" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                    <input type="text" id="external_ticketID" name="external_ticketID" class="form-control" value="" disabled/>
                                @endif
                            </div>
                            <div class="col-4">
                                <label class="form-label">Czas obsługi zlecenia</label>
                                <input type="text" id="time_spent" class="form-control" value="{{ date('H:i', strtotime($ticket->time_spent)) }}" disabled/>
                            </div>
                        @endif
                    </div>

                    <!-- Button group -->
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            @if ($ticket->ticket_status == -1)
                                <input type="button" class="btn btn-success" id="accept" data-bs-toggle="modal" data-bs-target="#modal" value="Zatwierdź zgłoszenie" data-id="acceptTicket"/>
                                <input type="button" class="btn btn-danger" id="close" data-bs-toggle="modal" data-bs-target="#modal" value="Odrzuć" data-id="rejectTicket"/>
                                <input name="takeTicket" class="btn btn-warning" type="Submit" value="Podejmij zgłoszenie"/>
                            @elseif ($ticket->ticket_status == 0)
                                <input name="takeTicket" class="btn btn-warning" type="Submit" value="Podejmij zgłoszenie"/>
                            @elseif ($ticket->ticket_status == 1)
                                <input name="editTicket" class="btn btn-success" type="Submit" value="Zapisz zmiany"/>
                                <input type="button" class="btn btn-danger" id="close" data-bs-toggle="modal" data-bs-target="#modal" value="Zamknij zgłoszenie" data-id="closeTicket"/>
                                <span class="btn-group" style="float: right">
                                    <button name="timerAction" class="btn-sm btn-light-outline" style="margin-left:1%" type="button" value="5">+ 5 minut</button>
                                    <button name="timerAction" class="btn-sm btn-secondary" style="margin-left:1%" type="button" value="15">+ 15 minut</button>
                                    <button name="timerAction" class="btn-sm btn-dark" style="margin-left:1%" type="button" value="30">+ 30 minut</button>
                                </span>
                            @elseif ($date_now < $date_closed && $ticket->target_department == null)
                                <input name="reopenTicket" id="reopenTicket" class="btn btn-primary" style="margin-left:1%" type="Submit" value="Otwórz ponownie zgłoszenie"/>
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
                                    <p id="modalContent"></p>
                                    <textarea class="form-control" id="closingNotes" name="closingNotes" maxlength="250"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">Anuluj</button>
                                    <input type="Submit" id="confirmClose" name="" value="Potwierdź" class="btn btn-danger"/>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="col">
                    <p class="fs-4 border-bottom">Załączniki</p>
                    @if ($ticket->ticket_status != 2)
                    <div class="form-group top-margin">
                        <label class="form-label">Dodaj załącznik (max 3 pliki do 5MB każdy)</label><br/>
                            <div class="dropzone" id="myDropzone">
                            <div class="data-dz-message"><span></span></div>
                         </div>
                    </div>
                    @endif
                    @if ($attachments != null)
                        <div class="row align-items-end">
                            @foreach ($attachments as $attachment)
                                @switch ($attachmentsDisplay[$attachment->file_name])
                                    @case ('image')
                                        <div class="col-2 text-center">
                                            <a href="{{ url('public/storage/'.$attachment->file_path.$attachment->file_name) }}" data-lightbox="image" data-title="{{ $attachment->file_name }}">
                                                <img src="{{ url('public/storage/'.$attachment->file_path.$attachment->file_name) }}" class="img-fluid" style="min-width: 100%; min-height: 100%; width: auto; height: auto;"/>
                                            </a>
                                        </div>
                                        @break
                                    @case ('download')
                                        <div class="col-2 text-center">
                                        <img src="{{ asset('public/img/download-icon.png') }}" class="img-fluid" style="max-width: 50%; max-height: 50%; width: auto; height: auto;"/>
                                            <label for="download" class="form-label">{{ $attachment->file_name }}</label><br/>
                                            <a href="{{ url('public/storage/'.$attachment->file_path.$attachment->file_name) }}" download="{{ $attachment->file_name }}">
                                                <button class="btn btn-primary" name="download"><i class="fa fa-download"></i> Pobierz załącznik</button>
                                            </a>
                                        </div>
                                        @break
                                    @default
                                        @break
                                @endswitch
                            @endforeach
                        </div>
                    @else
                        Brak załącznika
                    @endif
                </div>
                <div class="row" style="margin-top:1vw;">
                    <div class="col">
                        <p class="fs-4 border-bottom">Wiadomość do zgłoszenia</p>
                        <span class="lead" style="overflow-wrap: break-word;">
                            @if($ticket->ticket_message != null)
                                {{ $ticket->ticket_message }}
                            @else
                                Brak wiadomości
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
                            <label class="form-label">Treść notatki (max 250 znaków)</label>
                            <textarea class="form-control" name="noteContents" maxlength="250"></textarea><br/>
                            <input name="addNote" class="btn btn-primary" type="Submit" value="Dodaj notatkę"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab">
                <div class="row" style="margin-top:1vw;">
                    <div class="col">
                        @foreach ($history as $data)
                            <div class="col rounded shadow" style="background: white; margin-top:1vw; padding: 1vw 1vw 0.5vw 1vw;">
                                <p class="fs-5 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Edytowane przez {{ $data->username }} dnia {{ $data->date_modified }}</p>
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
                <p class="fs-5 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Notatka dodana {{ $note->created_at }} przez {{ $note->username }}</p>
                <p class="lead" style="overflow-wrap: break-word;">{{ $note->contents }}</p>
            </div>
        @endforeach
    </div>

    <script>
        $('#prioritySelect').val({{ $ticket->priority }});
        $('#problemSelect').val($('<div />').html('{{ $ticket->problem }}').text());
		$('#ownerSelect').val($('<div />').html('{{ $ticket->owner }}').text());

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

        if ($('#ownerSelect').val() == null){
            $('#ownerSelect').append('<option value="'+ '{{ $ticket->owner }}' +'">'+ '{{ $ticket->owner }}' +'</option>');
            $('#ownerSelect').val($('<div />').html('{{ $ticket->owner }}').text());
        }
        else{
            $('#ownerSelect').val($('<div />').html('{{ $ticket->owner }}').text());
        }

        $('#isExternal').click(function() {
            if ($('#isExternal').is(':checked')){
                $('#external_ticketID').removeAttr('disabled', 'disabled');
            }
            else if (!$('#isExternal').is(':checked')){
                $('#external_ticketID').prop('disabled', 'disabled');

            }
        });

        $("#close, #accept").click(function() {
            var type = $(this).attr('data-id');
            switch (type) {
                case 'rejectTicket':
                    $('#confirmClose').attr('name', 'rejectTicket');
                    $('#modalLabel').text('Odrzuć zgłoszenie');
                    $('#modalContent').text('Przed zamknięciem dodaj krótką notatkę (max 250 znaków).');
                    break;
                case 'closeTicket':
                    $('#confirmClose').attr('name', 'closeTicket');
                    $('#modalLabel').text('Zamknij zgłoszenie');
                    $('#modalContent').text('Przed zamknięciem dodaj krótką notatkę (max 250 znaków).');
                    break;
                case 'acceptTicket':
                    $('#confirmClose').attr('name', 'acceptTicket');
                    $('#modalLabel').text('Zatwierdź zgłoszenie');
                    $('#modalContent').text('Przed zatwierdzeniem dodaj krótką notatkę (max 250 znaków).');
                    break;
            }
            $("#closingNotes").prop('required', true);
        });

        $(document).on('show.bs.modal','#modal', function () {
            $("#closingNotes").prop('required', true);
        });
        $(document).on('hide.bs.modal','#modal', function () {
            $("#closingNotes").prop('required', false);
        });

        if ("{{ $ticket->date_closed }}"){
            countdown();
        }

        function countdown(){
            var countdownStart = new Date("{{ $ticket->date_closed }}").getTime() + 48 * 60 * 60 * 1000;

            var x = setInterval(function() {

            var now = new Date().getTime();

            var distance = countdownStart - now;

            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("reopenTicket").value = "Otwórz ponownie zgłoszenie (" +
                ('0' + hours).slice(-2) + ":" + ('0' + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2) + ")";

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
            dictDefaultMessage: '<img src="{{ asset('public/img/upload-icon.png') }}" class="img-fluid"/><br/> Kliknij tutaj lub upuść plik aby wysłać',
            dictFileTooBig: "Wielkość pliku przekracza 5MB",
            dictInvalidFileType: "Nieprawidłowy typ pliku",
            dictCancelUpload: "Anuluj wysyłanie",
            dictUploadCanceled: "Anulowano wysyłanie",
            dictRemoveFile: "Usuń plik",
            dictMaxFilesExceeded: "Przekroczono dozwoloną ilość plików",
            //acceptedFiles: 'image/*',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            }
        });

    </script>
@endsection

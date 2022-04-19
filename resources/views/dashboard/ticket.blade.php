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
    $countdown = $date_now->diff($date_closed, true);
@endphp
    <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
        <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Szczegóły zgłoszenia</p>
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
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
                                    @if (isset($ticket->target_department))
                                        <option value="{{ $ticket->target_department }}" selected>{{ $ticket->target_department }}</option>
                                    @elseif ($department->department_name == $ticket->department)
                                        <option value="{{ $department->department_name }}" selected>{{ $department->department_name }}</option>
                                    @else
                                        <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Problem</label>
                            <select id="problemSelect" name="problemSelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                @foreach ($problems as $problem)
                                    @if (isset($ticket->target_department))
                                        <option value="{{ $ticket->problem }}" selected>{{ $ticket->problem }}</option>
                                    @elseif ($problem->problem_name == $ticket->problem)
                                        <option value="{{ $problem->problem_name }}" selected>{{ $problem->problem_name }}</option>
                                    @else
                                        <option value="{{ $problem->problem_name }}">{{ $problem->problem_name }}</option>
                                    @endif
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
                        @if ($ticket->ticket_status != 0)
                            <hr>
                            <div class="col-4">
                                <label class="form-label">Osoba odpowiedzialna</label>
                                <select id="ownerSelect" name="ownerSelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                    @foreach ($staffMembers as $member)
                                        @if ($member->name == $ticket->owner)
                                            <option value="{{ $member->name }}" selected>{{ $member->name }}</option>
                                        @elseif ($member->login != 'root')
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
                                <input type="text" class="form-control" value="{{ date('H:i', strtotime($ticket->time_spent)) }}" disabled/>
                            </div>
                        @endif
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            @if ($ticket->ticket_status == -1)
                                <input name="acceptTicket" class="btn btn-success" type="Submit" value="Zatwierdź zgłoszenie"/>
                            @elseif ($ticket->ticket_status == 0)
                                <input name="takeTicket" class="btn btn-warning" type="Submit" value="Podejmij zgłoszenie"/>
                            @elseif ($ticket->ticket_status == 1)
                                <input name="editTicket" class="btn btn-success" type="Submit" value="Zapisz zmiany"/>
                                <input name="closeTicket" class="btn btn-danger" style="margin-left:1%" type="Submit" value="Zamknij zgłoszenie"/>
                                <span class="btn-group" style="float: right">
                                    <button name="timerAction" class="btn-sm btn-light-outline" style="margin-left:1%" type="Submit" value="5">+ 5 minut</button>
                                    <button name="timerAction" class="btn-sm btn-secondary" style="margin-left:1%" type="Submit" value="15">+ 15 minut</button>
                                    <button name="timerAction" class="btn-sm btn-dark" style="margin-left:1%" type="Submit" value="30">+ 30 minut</button>
                                </span>
                            @elseif ($date_now < $date_closed && $ticket->target_department == null)
                                <input name="reopenTicket" class="btn btn-primary" style="margin-left:1%" type="Submit" value="Otwórz ponownie zgłoszenie {{ $countdown->format('%H:%I:%S') }}"/>
                            @endif
                        </div>
                    </div>
                </form>
                <div class="col">
                    <p class="fs-4 border-bottom">Załącznik</p>
                    @if ($attachment != null)
                        @switch ($attachmentDisplay)
                            @case ('image')
                                <a href="{{ url('public/storage/'.$attachment->file_path.$attachment->file_name) }}" data-lightbox="image" data-title="{{ $attachment->file_name }}">
                                    <img src="{{ url('public/storage/'.$attachment->file_path.$attachment->file_name) }}" id="attachment" style="width:400px; height:250px;"/>
                                </a>
                                @break
                            @case ('download')
                                <label for="download" class="form-label">Plik: {{ $attachment->file_name }}</label><br/>
                                <a href="{{ url('public/storage/'.$attachment->file_path.$attachment->file_name) }}" download="{{ $attachment->file_name}}">
                                    <button class="btn btn-primary" name="download"><i class="fa fa-download"></i> Pobierz załącznik</button>
                                </a>
                                @break
                            @default
                                @break
                        @endswitch
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

        $('#isExternal').click(function() {
            if ($('#isExternal').is(':checked')){
                $('#external_ticketID').removeAttr('disabled', 'disabled');
            }
            else if (!$('#isExternal').is(':checked')){
                $('#external_ticketID').prop('disabled', 'disabled');

            }
        })
    </script>
@endsection

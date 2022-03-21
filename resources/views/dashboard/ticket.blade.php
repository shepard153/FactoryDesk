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
                                    @if($ticket->ticket_status == 0)
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
                            <label class="form-label">Nazwa</label>
                            <input type="text" class="form-control" value="{{ $ticket->name }}" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Zgłaszający</label>
                            <input type="text" class="form-control" value="{{ $ticket->username }}" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Obszar/dział produkcji</label>
                            <input type="text" class="form-control" value="{{ $ticket->zone }}" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Stanowisko</label>
                            <input type="text" class="form-control" value="{{ $ticket->position }}" disabled/>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <label class="form-label">Dział</label>
                            <select id="departmentSelect" name="departmentSelect" class="form-select" {{ $ticket->ticket_status == '2' ? 'disabled' : null }}>
                                @foreach ($departments as $department)
                                    @if ($department->department_name == $ticket->department)
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
                                    @if ($problem->problem_name == $ticket->problem)
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
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            @if ($ticket->ticket_status == 0)
                                <input name="takeTicket" class="btn btn-warning" type="Submit" value="Podejmij zgłoszenie"/>
                            @elseif ($ticket->ticket_status == 1)
                                <input name="editTicket" class="btn btn-success" type="Submit" value="Zapisz zmiany"/>
                                <input name="closeTicket" class="btn btn-danger" style="margin-left:1%" type="Submit" value="Zamknij zgłoszenie"/>
                            @elseif ($date_now < $date_closed)
                                <input name="reopenTicket" class="btn btn-primary" style="margin-left:1%" type="Submit" value="Otwórz ponownie zgłoszenie {{ $countdown->format('%H:%I:%S') }}"/>
                            @endif
                        </div>
                    </div>
                </form>
                <div class="col">
                    <p class="fs-4 border-bottom">Załącznik</p>
                        @if ($attachment != null)
                            <img src="{{ url('public/storage/'.$attachment->file_path.$attachment->file_name) }}" id="attachment" style="width:350px; height:250px;"/>
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
        var imgBig = false;
        $('#attachment').click(function() {
            if (imgBig == false){
                $(this).height(400);
                $(this).width(600);
                document.getElementById('attachment').scrollIntoView({ behavior: 'smooth' });
                imgBig = true;
            }
            else{
                $(this).height(250);
                $(this).width(350);
                imgBig = false;
            }
        });

        $(document).ready(function() {
            $('#departmentSelect').on('change', function() {
                var department = $(this).val();
                if(department) {
                    $.ajax({
                        url: 'ajax/'+department,
                        type: "GET",
                        dataType: "json",
                        success:function(problemData) {

                            $('#problemSelect').empty();
                            $('#problemSelect').removeAttr('disabled', 'disabled');
                            $.each(problemData, function(key, value) {
                                $('#problemSelect').append('<option value="'+ value['problem_name'] +'">'+ value['problem_name'] +'</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection

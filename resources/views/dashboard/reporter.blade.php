@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
        <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Formularz raportowania</p>
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form method="post" action="{{ url('getReport') }}">
            @csrf
            <div class="row" style="margin-top:1vw;">
                <h4>Format pliku</h4>
                <div class="col-2">
                    <input type="radio" name="fileFormat" class="form-check-input" value="csv" checked required>
                    <label class="form-label">CSV (Excel)</label>
                </div>
                <div class="col-3">
                    <input type="radio" name="fileFormat" class="form-check-input" value="pdf" required disabled>
                    <label class="form-label">PDF</label>
                </div>
            </div>
            <div class="row" style="margin-top:1vw;">
                <h4>Zakres wyszukiwania</h4>
                <div class="alert alert-warning"><i class="fa fa-circle-exclamation"></i> &nbsp;
                    Pod uwagę brana jest data utworzenia zgłoszenia!
                </div>
                <div class="col-2">
                    <label class="form-label">Data początkowa</label>
                    <input type="date" name="startDate" class="form-control" required>
                </div>
                <div class="col-2">
                    <label class="form-label">Data końcowa</label>
                    <input type="date" name="endDate" class="form-control" required>
                </div>
            </div>
            <div class="row" style="margin-top:1vw;">
                <h4>Kolumny do exportu</h4>
                <div class="alert alert-info"><i class="fa fa-info-circle"></i> &nbsp;
                    Wybierz kolumny, które mają pojawić się w pliku wyjściowym i kliknij <b>Generuj raport</b>, aby otrzymać raport.<br/>
                    Domyślnie zaznaczone są wszystkie opcje.
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isID" value="department_ticketID" class="form-check-input" checked disabled>
                    <input type="hidden" name="isID" value="department_ticketID" />
                    <label class="form-label">Identyfikator</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isDepartment" value="department" class="form-check-input" checked disabled>
                    <input type="hidden" name="isDepartment" value="department" />
                    <label class="form-label">Dział</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isDevice" value="device_name" class="form-check-input" checked>
                    <label class="form-label">Nazwa urządzenia</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isUser" value="username" class="form-check-input" checked>
                    <label class="form-label">Zgłaszający</label>
                </div>
            </div>
            <div class="row" style="margin-top:1vw;">
                <div class="col-2">
                    <input type="checkbox" name="isZone" value="zone" class="form-check-input" checked>
                    <label class="form-label">Obszar produkcji</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isPosition" value="position" class="form-check-input" checked>
                    <label class="form-label">Stanowisko</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isProblem" value="problem" class="form-check-input" checked>
                    <label class="form-label">Problem</label>
                </div>
                <div class="col">
                    <input type="checkbox" name="isExternal" value="external_ticketID" class="form-check-input" checked>
                    <label class="form-label">Zgłoszenie zewnętrzne/Kod kotła</label>
                </div>
            </div>
            <div class="row" style="margin-top:1vw;">
                <div class="col-2">
                    <input type="checkbox" name="isPriority" value="priority" class="form-check-input" checked>
                    <label class="form-label">Priorytet</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isTime" value="time_spent" class="form-check-input" checked>
                    <label class="form-label">Czas obsługi</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isMessage" value="ticket_message" class="form-check-input" checked>
                    <label class="form-label">Wiadomość</label>
                </div>
                <div class="col-3">
                    <input type="checkbox" name="isOwner" value="owner" class="form-check-input" checked>
                    <label class="form-label">Osoba odpowiedzialna</label>
                </div>
            </div>
            <div class="row" style="margin-top:1vw;">
                <div class="col-2">
                    <input type="checkbox" name="isCreated" value="date_created" class="form-check-input" checked>
                    <label class="form-label">Data utworzenia</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isTaken" value="date_opened" class="form-check-input" checked>
                    <label class="form-label">Data podjęcia</label>
                </div>
                <div class="col-2">
                    <input type="checkbox" name="isClosed" value="date_closed" class="form-check-input" checked>
                    <label class="form-label">Data zamknięcia</label>
                </div>
            </div>

            <div class="row" style="margin-top:1vw;">
                <div class="col">
                    <input type="Submit" class="btn btn-success" id="accept" name="generateReport" value="Generuj raport"/>
                </div>
            </div>
        </form>
    </div>

@endsection

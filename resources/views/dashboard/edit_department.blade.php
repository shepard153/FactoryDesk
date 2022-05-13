@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
        <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Edytuj dział</p>
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
        <form class="row" method="post" enctype="multipart/form-data" action="{{ url('editDepartmentAction/'.$department->departmentID) }}">
            @csrf
            <div class="col-lg-6">
                <p class="fs-4" style="padding: 0vw 0vw 0.6vw 0vw;">Szczegóły</p>
                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa działu</label>
                    <input type="text" class="form-control" id="department_name" name="department_name" value="{{ $department->department_name }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Miniaturka do wyświetlenia (pliki *.jpg, *.png) (opcjonalnie)</label><br/>
                    <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg" aria-label="Upload">
                </div>
                <div class="mb-3">
                    <input class="form-check-input" type="checkbox" name="isHidden" id="isHidden" {{ $department->isHidden ? 'checked' : 'null' }}>
                    <label class="form-check-label" for="isHidden">Dział ukryty</label>
                </div>
                <div class="alert alert-info">
                    Zaznacz pole poniżej jeśli chcesz, aby zgłoszenia zanim trafią do tego działu zostały zatwierdzone przez inny.
                </div>
                <div class="mb-3">
                    <input class="form-check-input" type="checkbox" id="acceptance" name="acceptance" {{ $department->acceptance_from ? 'checked' : 'null' }}>
                    <label class="form-check-label" for="acceptance">Zatwierdzanie zgłoszeń przez inny dział</label>
                </div>
                <div class="mb-3" id="acceptanceDiv" {{ $department->acceptance_from != null ? 'null' : "style=display:none"; }}>
                    <label class="form-label">Dział zatwierdzający</label>
                    <select id="acceptance_from" name="acceptance_from" class="form-select">
                        @foreach ($departments as $department)
                            @if ($department->department_name == $department->acceptance_from)
                                <option value="{{ $department->department_name }}" selected>{{ $department->department_name }}</option>
                            @else
                                <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col">
                <p class="fs-4" style="padding: 0vw 0vw 0.6vw 0vw;">Powiadomienia</p>
                <div class="mb-3">
                    <label for="name" class="form-label">Microsoft Teams Webhook</label>
                    <input type="text" class="form-control" id="teams_webhook" name="teams_webhook" value="{{ $department->teams_webhook }}">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Adres e-mail</label>
                    <input type="email" class="form-control" id="department_email" name="department_email" value="" disabled>
                </div>
            </div>
            <div class="mb-3">
                <input name="submit" class="btn btn-primary" type="Submit"/>
            </div>
        </form>
    </div>
    <script>
        $('#acceptance').click(function() {
            $("#acceptanceDiv").toggle(300);
        });
    </script>
@endsection

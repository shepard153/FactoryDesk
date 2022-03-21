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
        <form class="col-lg-8" method="post" enctype="multipart/form-data" action="{{ url('editDepartmentAction/'.$department->departmentID)}}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nazwa działu</label>
                <input type="text" class="form-control" id="department_name" name="department_name" value="{{ $department->department_name }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Miniaturka do wyświetlenia (pliki *.jpg, *.png) (opcjonalnie)</label><br/>
                <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg" aria-label="Upload">
            </div>
            <div class="mb-3">
                <input name="submit" class="btn btn-primary" type="Submit"/>
            </div>
        </form>
    </div>
@endsection

@extends('ticket/ticket_template')
 
@section('title', 'RUGDesk')
 
@section('navbar')
    @parent
 
@endsection
 
@section('content')
    <div class="alert alert-danger text-center">
        <h4>System testowy - pomimo wysłanego zgłoszenia poinformuj przełożonego.</h4>
    </div>
    <p class="fs-4 border-bottom text-center">Wybierz dział z którym chcesz się skontaktować.</p>

    <div class="row justify-content-md-center top-margin">
        @php
            $i = 1
        @endphp
        @foreach ($departments as $department)
            <div class='col-3'>
                @php $departmentName = json_decode(str_replace(" ", "%20", json_encode($department->department_name))) @endphp
                <a href="{{ url('ticket_step2/'.$departmentName) }}" style="text-decoration:none">
                    @if ($department->image_path == null)
                        <div class="rounded alternate">{{ $department->department_name }}</div>
                    @else
                        <img src="{{ url('public/storage/'.$department->image_path) }}" class='rounded' width='250' height='250' alt='{{$department->department_name}}'>
                    @endif
                </a>
            </div>
            @if ($i % 3 == 0)
                </div><div class="row justify-content-md-center top-margin">
            @endif
            @php
                $i++
            @endphp
        @endforeach
    </div>
@endsection
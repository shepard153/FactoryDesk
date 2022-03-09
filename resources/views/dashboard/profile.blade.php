@extends('dashboard/dashboard_template')
 
 @section('title', 'RUGDesk')
  
 @section('sidebar')
     @parent
  
 @endsection
  
 @section('content')
    <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
        <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Ustawienia konta</p>
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
        <form class="col-lg-8" method="post" action="{{ url('modifySelfStaff')}}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email (zmiana możliwa bez podawania hasła)</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Stare hasło <span style="color:red">*</span></label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nowe hasło <span style="color:red">*</span></label>
                <input type="password" class="form-control" id="newPassword" name="newPassword">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Powtórz nowe hasło <span style="color:red">*</span></label>
                <input type="password" class="form-control" id="newPassword_confirmation" name="newPassword_confirmation">
            </div>
            <div class="mb-3">
                <input name="submit" class="btn btn-primary" type="Submit"/>
            </div>
        </form>
    </div>
@endsection
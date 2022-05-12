@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
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
        <form class="row" method="post" action="{{ route('setSettings') }}">
            @csrf
            <div class="col-4">
                <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Dashboard</p>
                <div class="mb-3">
                    <label for="dashboard_refreshTime" class="form-label">Interwał odświeżania (w sekundach)</label>
                    <input type="text" class="form-control" name="dashboard_refreshTime" value="{{ $settings['dashboard_refreshTime'] }}">
                </div>
                <div class="mb-3">
                    <label for="dashboard_newestToDisplay" class="form-label">Ilość najnowszych zgłoszeń do wyświetlenia</label>
                    <input type="text" class="form-control" name="dashboard_newestToDisplay" value="{{ $settings['dashboard_newestToDisplay'] }}">
                </div>
            </div>
            <div class="mb-3">
                <input name="submit" class="btn btn-primary" type="Submit"/>
            </div>
        </form>
    </div>
@endsection

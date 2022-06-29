@extends('dashboard/dashboard_template')

 @section('title', 'RUGDesk')

 @section('sidebar')
     @parent

 @endsection

 @section('content')
    <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
        <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_staff.edit_user') }}</p>
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
        <form class="col-lg-8" method="post" action="{{ url('editMemberAction/'.$member->staffID)}}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('dashboard_staff.login') }}</label>
                <input type="text" class="form-control" id="login" name="login" value="{{ $member->login }}" disabled>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('dashboard_staff.new_password') }}</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">{{ __('dashboard_staff.username') }}</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $member->name }}">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('dashboard_staff.email') }}</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ $member->email }}">
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('dashboard_staff.department') }}</label>
                <select id="departmentSelect" name="departmentSelect" class="form-select">
                    @foreach ($departments as $department)
                        @if ($department->department_name == $member->department)
                            <option value="{{ $department->department_name }}" selected>{{ $department->department_name }}</option>
                        @else
                            <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                @if ($member->isAdmin == 1)
                    <input class="form-check-input" type="checkbox" name="isAdmin" id="isAdmin" checked>
                @else
                    <input class="form-check-input" type="checkbox" name="isAdmin" id="isAdmin">
                @endif
                <label class="form-check-label" for="isAdmin">{{ __('dashboard_staff.admin_account') }}</label>
            </div>
            <div class="mb-3">
                <input name="submit" class="btn btn-primary" type="Submit" value="{{ __('dashboard_staff.save_changes') }}"/>
            </div>
        </form>
    </div>
@endsection

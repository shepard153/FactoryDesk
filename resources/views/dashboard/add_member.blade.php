@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
    <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_staff.add_user') }}</p>
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
    <form class="col-lg-8" method="post" action="{{ route('addMemberAction') }}">
      @csrf
      <div class="mb-3">
        <label for="username" class="form-label">{{ __('dashboard_staff.username') }}</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">{{ __('dashboard_staff.email') }}</label>
        <input type="text" class="form-control" id="email" name="email">
      </div>
      <div class="mb-3">
        <label for="login" class="form-label">{{ __('dashboard_staff.login') }}</label>
        <input type="text" class="form-control" id="login" name="login" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">{{ __('dashboard_staff.password') }}</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="mb-3">
        <label class="form-label">{{ __('dashboard_staff.department') }}</label>
        <select id="departmentSelect" name="departmentSelect" class="form-select" required>
          @foreach ($departments as $departmet)
            <option value="{{ $departmet->department_name }}">{{ $departmet->department_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <input class="form-check-input" type="checkbox" name="isAdmin" id="isAdmin">
        <label class="form-check-label" for="isAdmin">{{ __('dashboard_staff.admin_account') }}</label>
      </div>
      <div class="mb-3">
        <input name="submit" class="btn btn-primary" type="Submit" value="{{ __('dashboard_staff.create_user') }}"/>
      </div>
    </form>
  </div>
@endsection


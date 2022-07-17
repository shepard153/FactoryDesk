@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
    <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_profile.account_settings') }}</p>
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
        <label for="email" class="form-label">{{ __('dashboard_profile.email') }}</label>
        <p>{{ auth()->user()->email }}</p>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">{{ __('dashboard_profile.password') }} <span style="color:red">*</span></label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">{{ __('dashboard_profile.new_password') }} <span style="color:red">*</span></label>
        <input type="password" class="form-control" id="newPassword" name="newPassword">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">{{ __('dashboard_profile.new_password_confirm') }} <span style="color:red">*</span></label>
        <input type="password" class="form-control" id="newPassword_confirmation" name="newPassword_confirmation">
      </div>
      <div class="mb-3">
        <input name="submit" class="btn btn-primary" type="Submit" value="{{ __('dashboard_profile.save_changes') }}"/>
      </div>
    </form>
  </div>
@endsection

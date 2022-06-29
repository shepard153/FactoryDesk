@extends('dashboard/dashboard_template')

@section('title', 'RUGDesk')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
    <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_departments.create_department') }}</p>
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
    <form class="row" method="post" enctype="multipart/form-data" action="{{ route('addDepartmentAction') }}">
      @csrf
      <div class="col-lg-6">
        <p class="fs-4" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_departments.details') }}</p>
        <div class="mb-3">
          <label for="department_name" class="form-label">{{ __('dashboard_departments.department_name') }}</label>
          <input type="text" class="form-control" id="department_name" name="department_name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('dashboard_departments.img_to_display') }}</label><br/>
          <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg" aria-label="Upload">
        </div>
        <div class="mb-3">
          <input class="form-check-input" type="checkbox" name="isHidden" id="isHidden">
          <label class="form-check-label" for="isHidden">{{ __('dashboard_departments.hidden_department') }}</label>
        </div>
        <div class="alert alert-info">
          <i class="fa fa-info-circle"></i> {!! __('dashboard_departments.acceptance_info') !!}
        </div>
        <div class="mb-3">
          <input class="form-check-input" type="checkbox" id="acceptance" name="acceptance">
          <label class="form-check-label" for="acceptance">{{ __('dashboard_departments.acceptance_mode') }}</label>
        </div>
        <div class="mb-3" id="acceptanceDiv" style="display: none;">
          <label class="form-label">{{ __('dashboard_departments.accepting_department') }}</label>
          <select id="acceptance_from" name="acceptance_from" class="form-select">
            @foreach ($departments as $departmet)
              <option value="{{ $departmet->department_name }}">{{ $departmet->department_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col">
        <p class="fs-4" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_departments.notifications') }}</p>
        <div class="mb-3">
          <label for="name" class="form-label">{{ __('dashboard_departments.teams_webhook') }}</label>
          <input type="text" class="form-control" id="teams_webhook" name="teams_webhook">
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">{{ __('dashboard_departments.discord_webhook') }}</label>
          <input type="text" class="form-control" id="discord_webhook" name="discord_webhook">
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">{{ __('dashboard_departments.mailing') }}</label>
          <input type="email" class="form-control" id="department_email" name="department_email" value="" disabled>
        </div>
      </div>
      <div class="mb-3">
        <input name="submit" class="btn btn-primary" type="Submit" value="{{ __('dashboard_departments.create') }}"/>
      </div>
    </form>
  </div>

  <script>
    $('#acceptance').click(function() {
        $("#acceptanceDiv").toggle(300);
    });
  </script>
@endsection

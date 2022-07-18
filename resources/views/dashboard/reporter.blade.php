@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
    <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">{{ __('dashboard_reporting.form_header') }}</p>
    @if (session('message'))
      <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    <form method="post" action="{{ url('getReport') }}">
      @csrf
      <div class="row" style="margin-top:1vw;">
        <h4>{{ __('dashboard_reporting.file_type') }}</h4>
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
        <h4>{{ __('dashboard_reporting.search_range') }}</h4>
        <div class="alert alert-warning"><i class="fa fa-circle-exclamation"></i> &nbsp;
          {{ __('dashboard_reporting.search_range_info') }}
        </div>
        <div class="col-2">
          <label class="form-label">{{ __('dashboard_reporting.start_date') }}</label>
          <input type="date" name="startDate" class="form-control" required>
        </div>
        <div class="col-2">
          <label class="form-label">{{ __('dashboard_reporting.end_date') }}</label>
          <input type="date" name="endDate" class="form-control" required>
        </div>
      </div>
      <div class="row" style="margin-top:1vw;">
        <h4>{{ __('dashboard_reporting.cols_to_export') }}</h4>
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> &nbsp;
          {!! __('dashboard_reporting.cols_to_export_info') !!}
        </div>
        <div class="col-2">
          <input type="checkbox" name="isID" value="department_ticketID" class="form-check-input" checked disabled>
          <input type="hidden" name="isID" value="department_ticketID" />
          <label class="form-label">{{ __('dashboard_reporting.col_id') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isDepartment" value="department" class="form-check-input" checked disabled>
          <input type="hidden" name="isDepartment" value="department" />
          <label class="form-label">{{ __('dashboard_reporting.col_department') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isDevice" value="device_name" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_device') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isUser" value="username" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_raised_by') }}</label>
        </div>
      </div>
      <div class="row" style="margin-top:1vw;">
        <div class="col-2">
          <input type="checkbox" name="isZone" value="zone" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_zone') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isPosition" value="position" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_position') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isProblem" value="problem" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_problem') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isPriority" value="priority" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_priority') }}</label>
        </div>
      </div>
      <div class="row" style="margin-top:1vw;">
        <div class="col-2">
          <input type="checkbox" name="isTime" value="time_spent" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_service_time') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isMessage" value="ticket_message" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_message') }}</label>
        </div>
        <div class="col-3">
          <input type="checkbox" name="isOwner" value="owner" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_owner') }}</label>
        </div>
      </div>
      <div class="row" style="margin-top:1vw;">
        <div class="col-2">
          <input type="checkbox" name="isCreated" value="date_created" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_date_created') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isTaken" value="date_opened" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_date_opened') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isClosed" value="date_closed" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_date_closed') }}</label>
        </div>
      </div>
      <div class="row" style="margin-top:1vw;">
        <div class="col-2">
          <input type="checkbox" name="isType" value="ticket_type" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_ticket_type') }}</label>
        </div>
        <div class="col-2">
          <input type="checkbox" name="isNotes" value="closing_notes" class="form-check-input" checked>
          <label class="form-label">{{ __('dashboard_reporting.col_closing_notes') }}</label>
        </div>
      </div>
      <div class="row" style="margin-top:1vw;">
        <div class="col">
          <input type="Submit" class="btn btn-success" id="accept" name="generateReport" value="{{ __('dashboard_reporting.generate_report') }}"/>
        </div>
      </div>
    </form>
  </div>
@endsection

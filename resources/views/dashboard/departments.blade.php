@extends('dashboard/dashboard_template')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
    <a href="{{ url('addDepartment') }}" class="btn btn-success btn-sm" style="float:right; margin: 0.7vw 1vw 0vw 0vw;">{{ __('dashboard_departments.create_department') }}</a>
    <p class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">{{ __('dashboard_departments.manage_departments') }}</p>
    @if (session('message'))
	  <div class="alert alert-danger">{{ session('message') }}</div>
    @endif
    <table class="table table-striped table-hover responsive table align-middle">
      @if ($departments != null)
        <thead>
          <tr>
            <td></td>
            <td><b>{{ __('dashboard_departments.department_name') }}</b></td>
            <td><b>{{ __('dashboard_departments.operations') }}</b></td>
          </tr>
        </thead>
        @foreach($departments as $department)
          <tr>
            <td style="background-image: url({{ 'storage/'.$department->image_path }}); background-repeat:no-repeat;background-size:40px 47px; width:40px; height:40px;"></td>
            <td style="width: 75%">{{ $department->department_name }}</td>
            <td>
              <a href="{{ url('department/'.$department->departmentID) }}" class="btn btn-success btn-sm">{{ __('dashboard_departments.operation_edit') }}</a>
              <button class="btn btn-danger btn-sm" name="delete" data-bs-toggle="modal" data-bs-target="#modal" value="{{ $department->department_name }}" data-id="{{ $department->departmentID }}">{{ __('dashboard_departments.operation_delete') }}</button>

              <!-- Modal confirmation window -->
              <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method="post" action="{{ url('deleteDepartmentAction') }}">
                  @csrf
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard_departments.remove_department') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p id="text"><!-- Text from JS script --></p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard_departments.remove_cancel') }}</button>
                        <button type="Submit" id="confirmDelete" name="confirmDelete" class="btn btn-danger">{{ __('dashboard_departments.remove_confirm') }}</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

            </td>
          </tr>
        @endforeach
      @else
        <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">{{ __('dashboard_departments.no_departments_found') }}</p>
      @endif
    </table>
  </div>
  <script>
    $("[name=delete]").click(function() {
        var buttonDelete = $(this).val();
        var departmentID = $(this).attr('data-id');
        $('#text').text("{{ __('dashboard_departments.remove_message') }}" + buttonDelete + "?");
        document.getElementById("confirmDelete").value = departmentID;
    });
  </script>
@endsection

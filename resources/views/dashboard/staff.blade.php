@extends('dashboard/dashboard_template')

@section('title', 'RUGDesk')

@section('sidebar')
  @parent

@endsection

@section('content')
  <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
    <a href="{{ url('addMember') }}" class="btn btn-success btn-sm" style="float:right; margin: 0.7vw 1vw 0vw 0vw;">{{ __('dashboard_staff.add_user') }}</a>
    <p class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">{{ __('dashboard_staff.manage_users') }}</p>
    @if (session('message'))
	  <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    <table class="table table-striped table-hover responsive">
      @if ($staffMembers != null)
        <thead>
          <tr>
            <td><b>{{ __('dashboard_staff.login') }}</b></td>
            <td><b>{{ __('dashboard_staff.username') }}</b></td>
            <td><b>{{ __('dashboard_staff.email') }}</b></td>
            <td><b>{{ __('dashboard_staff.department') }}</b></td>
            <td><b>{{ __('dashboard_staff.admin_account') }}</b</td>
            <td><b>{{ __('dashboard_staff.operations') }}</b></td>
          </tr>
        </thead>
        @foreach($staffMembers as $member)
          @if ($member->login != 'root')
            <tr>
              <td>{{ $member->login }}</td>
              <td>{{ $member->name }}</td>
              <td>{{ $member->email }}</td>
              <td>{{ $member->department }}</td>
              <td>{{ $member->isAdmin == 1 ? __('dashboard_staff.is_admin_yes') : __('dashboard_staff.is_admin_no') }}</td>
              <td>
                <a href="{{ url('staff/'.$member->staffID) }}" class="btn btn-success btn-sm">{{ __('dashboard_staff.operation_edit') }}</a>
                <button class="btn btn-danger btn-sm" name="delete" data-bs-toggle="modal" data-bs-target="#modal" value="{{ $member->login }}" data-id="{{ $member->staffID }}">{{ __('dashboard_staff.operation_delete') }}</button>

                <!-- Modal confirmation window -->
                <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <form method="post" action="{{ url('deleteMemberAction') }}">
                    @csrf
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard_staff.remove_user') }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p id="text"><!-- Text from JS script --></p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard_staff.remove_cancel') }}</button>
                          <button type="Submit" id="confirmDelete" name="confirmDelete" class="btn btn-danger">{{ __('dashboard_staff.remove_confirm') }}</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

              </td>
            </tr>
          @endif
        @endforeach
      @else
        <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">{{ __('dashboard_staff.no_users_found') }}</p>
      @endif
    </table>
  </div>
  <script>
    $("[name=delete]").click(function() {
      var buttonDelete = $(this).val();
      var staffID = $(this).attr('data-id');
      $('#text').text("{{ __('dashboard_staff.remove_account_message') }} " + buttonDelete + "?");
      document.getElementById("confirmDelete").value = staffID;
    });
  </script>
@endsection

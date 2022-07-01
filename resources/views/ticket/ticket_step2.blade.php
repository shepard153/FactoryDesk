@extends('ticket/ticket_template')

@section('title', 'RUGDesk')

@section('navbar')
    @parent

@endsection

@section('content')
  <form class="row mt-2" id="form">
    <div class="col-5 offset-md-1">
      @csrf
      <input type="hidden" name="department" id="department" value="{{ $department }}"/>
      <div class="form-group">
        <label class="form-label">{{ __('raise_ticket_form.device_name') }}</label>
        <input type="text" name="device_name" value="{{ $domain }}" class="form-control" readonly>
      </div>
      <div class="form-group mt-3">
        <label class="form-label">{{ __('raise_ticket_form.username') }}</label>
        <input type="text" name="username" value="User" class="form-control" readonly>
      </div>
      <div class="form-group mt-3">
        <label class="form-label">{{ __('raise_ticket_form.zone_label') }} <span style="color:red">*</span></label>
        <select id="zoneSelect" name="zoneSelect" class="form-select form-select-lg mb-3" required>
          <option value="">{{ __('raise_ticket_form.zone_select_default') }}</option>
          @foreach ($zones as $zone)
            <option value="{{ $zone->zone_name }}">{{ $zone->zone_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mt-3">
        <label class="form-label">{{ __('raise_ticket_form.position_label') }} <span style="color:red">*</span></label>
        <select id="positionSelect" name="positionSelect" class="form-select form-select-lg mb-3" disabled required>
          <option value="">{{ __('raise_ticket_form.position_select_default') }}</option>
          <!-- Positions list loaded through ajax call based on selected zone -->
        </select>
      </div>
      <div class="form-group mt-3">
        <label class="form-label">{{ __('raise_ticket_form.problem_label') }} <span style="color:red">*</span></label>
        <select id="problemSelect" name="problemSelect" class="form-select form-select-lg mb-3" disabled required>
          <option value="">{{ __('raise_ticket_form.problem_select_default') }}</option>
          <!-- Problems list loaded through ajax call based on selected position -->
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">{{ __('raise_ticket_form.message_box_label') }}</label><br/>
        <textarea class="form-control" name="message" maxlength="500"></textarea>
      </div>
      <div class="form-group mt-3">
        <label class="form-label">{{ __('dropzone.label') }}</label><br/>
        <div class="dropzone" id="myDropzone">
          <div class="data-dz-message"><span></span></div>
        </div>
      </div>
      <div class="form-group mt-3">
        <button id="submit" name="submit" class="btn btn-lg btn-primary" type="button" disabled>{{ __('raise_ticket_form.submit_form') }}</button>
      </div>
    </div>
    <div class="col-5 ie11-margin">
      <div class="form-group">
        <label class="form-label">{{ __('raise_ticket_form.priority_label') }}</label>
        <select id="prioritySelect" name="prioritySelect" class="form-select form-select-lg mb-3">
          <option value="0">{{ __('raise_ticket_form.priority_low') }}</option>
          <option value="2" default selected>{{ __('raise_ticket_form.priority_medium') }}</option>
          <option value="4">{{ __('raise_ticket_form.priority_high') }}</option>
        </select>
      </div>
      <div id="info" class="form-group alert alert-info text-center">
        <table class="table">
          <thead>
            <td>{{ __('raise_ticket_form.priority') }}</td>
            <td>{{ __('raise_ticket_form.priorities_desc') }}</td>
          </thead>
          <tr>
            <td>{{ __('raise_ticket_form.priority_low') }}</td>
            <td>{{ __('raise_ticket_form.priority_low_desc') }}</td>
          </tr>
          <tr>
            <td>{{ __('raise_ticket_form.priority_medium') }}</td>
            <td>{{ __('raise_ticket_form.priority_medium_desc') }}</td>
          </tr>
          <tr>
            <td>{{ __('raise_ticket_form.priority_high') }}</td>
            <td>{{ __('raise_ticket_form.priority_high_desc') }}</td>
          </tr>
        </table>
      </div>
    </div>
  </form>
  <script type="text/javascript">
    $(document).ready(function() {
        /**
         * Get positions based on selected zone.
         */
        $('#zoneSelect').on('change', function() {
            var zoneName = $(this).val();
            if(zoneName) {
                $.ajax({
                    url: 'ajax/zone/'+zoneName,
                    type: "GET",
                    dataType: "json",
                    success:function(positionData) {
                        $('#positionSelect').empty();
                        $('#positionSelect').removeAttr('disabled', 'disabled');
                        $('#positionSelect').append('<option>{{ __("raise_ticket_form.position_select_default") }}</option>');
                        $.each(positionData, function(key, value) {
                            $('#positionSelect').append('<option value="'+ value['position_name'] +'">'+ value['position_name'] +'</option>');
                        });
                    }
                });
            }else{
                $('#positionSelect').empty();
                $('#positionSelect').attr('disabled', 'disabled');
                $('#positionSelect').append('<option value="null">{{ __("raise_ticket_form.position_select_default") }}</option>');
                $('#problemSelect').empty();
                $('#problemSelect').attr('disabled', 'disabled');
                $('#problemSelect').append('<option value="null">{{ __("raise_ticket_form.problem_select_default") }}</option>');
                $('#submit').prop("disabled", true);
            }
        });

        /**
         * Get problems based on selected position.
         */
        $('#positionSelect').on('change', function() {
            var positionName = $(this).val();
            var department = $('#department').val();
            if (positionName != "Wybierz stanowisko" && positionName != null){
                $.ajax({
                    url: department + '/ajax/position/' + positionName,
                    type: "GET",
                    dataType: "json",
                    success:function(problemData) {
                        $('#problemSelect').empty();
                        $('#problemSelect').removeAttr('disabled', 'disabled');
                        $('#problemSelect').append('<option value="null">{{ __("raise_ticket_form.problem_select_default") }}</option>');
                        $.each(problemData, function(key, value) {
                            $('#problemSelect').append('<option value="'+ value['problem_name'] +'">'+ value['problem_name'] +'</option>');
                        });
                    }
                });
            }else{
                $('#problemSelect').empty();
                $('#problemSelect').attr('disabled', 'disabled');
                $('#problemSelect').append('<option value="null">{{ __("raise_ticket_form.problem_select_default") }}</option>');
                $('#submit').prop("disabled", true);
            }
        });

        /**
         * Final check if all form fields are selected. If true, enable submit button.
         */
        $('#problemSelect').on('change', function() {
            var problemName = $(this).val();
            if(problemName != "null" && problemName != "{{ __('raise_ticket_form.problem_select_default') }}") {
                $('#submit').removeAttr('disabled', 'disabled');
            }else{
                $('#submit').prop("disabled", true);
            }
        });
    });

    /**
     * Dropzone settings
     */
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone(".dropzone", {
        url: "{{route('sendTicket')}}",
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 3,
        maxFiles: 3,
        maxFilesize: 5,
        dictDefaultMessage: '<img src="{{ asset('public/img/upload-icon.png') }}" class="img-fluid" style="max-width:25%"/><br/> {{ __("dropzone.drop_here") }}',
        dictFileTooBig: "{{ __('dropzone.file_too_big') }}",
        dictInvalidFileType: "{{ __('dropzone.invalid_file_type') }}",
        dictCancelUpload: "{{ __('dropzone.cancel_upload') }}",
        dictUploadCanceled: "{{ __('dropzone.upload_canceled') }}",
        dictRemoveFile: "{{ __('dropzone.remove_file') }}",
        dictMaxFilesExceeded: "{{ __('dropzone.max_files_exceeded') }}",
        //acceptedFiles: 'image/*',
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        init: function() {
            dzClosure = this;

            document.getElementById("submit").addEventListener("click", function(e) {
                e.stopPropagation();
            });
            this.on('sending', function(file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                var data = $('#form').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
            });
            this.on('success', function(file, response){
                localStorage.setItem("id", response['id']);
                id = response['id'];
                window.location = "{{ url('ticket_sent') }}/" + id;
            });
            this.on("error", function(file, errormessage, xhr){
                if(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
                $('#submit').text('{{ __("raise_ticket_form.submit_form") }}');
            });
        }
    });

    /**
     * On submit check if files are uploaded. If not send fake blob file so the form can be processed
     * by dropzone.
     */
    $("#submit").on('click', function() {
        if (myDropzone.getQueuedFiles().length === 0) {
            var blob = new Blob();
            blob.upload = { 'chunked': myDropzone.defaultOptions };
            myDropzone.uploadFile(blob);
            spinnerTrigger();
        } else {
            myDropzone.processQueue();
            spinnerTrigger();
        }
    });

    /**
     * Trigger function for spinner animation on form send.
     */
    function spinnerTrigger(){
        $('#submit').text('');
        $('#submit').attr('disabled', 'disabled');
        $('#submit').append('<span class="spinner-border spinner-border-sm" role="status"></span>');
        $('#submit').append(' {{ __("raise_ticket_form.submitting") }}');
    }
  </script>
@endsection

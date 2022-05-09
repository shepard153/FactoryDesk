@extends('ticket/ticket_template')

@section('title', 'RUGDesk')

@section('navbar')
    @parent

@endsection

@section('content')
        <div class="row justify-content-end">
            <form class="row" method="post" enctype="multipart/form-data" action="{{ route('sendTicket') }}">
                <div class="col-5 offset-md-1">
                    @csrf
                    <input type="hidden" name="department" id="department" value="{{ $department }}"/>
                    <div class="form-group">
                        <label class="form-label">Nazwa komputera</label>
                        <input type="text" name="device_name" value="{{ $domain }}" class="form-control" readonly>
                    </div>
                    <div class="form-group top-margin">
                        <label class="form-label">Nazwa użytkownika</label>
                        <input type="text" name="username" value="User" class="form-control" readonly>
                    </div>
                    <div class="form-group top-margin">
                        <label class="form-label">Obszar/dział produkcji <span style="color:red">*</span></label>
                        <select id="zoneSelect" name="zoneSelect" class="form-select form-select-lg mb-3" required>
                            <option value="">Wybierz obszar produkcji</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->zone_name }}">{{ $zone->zone_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group top-margin">
                        <label class="form-label">Stanowisko <span style="color:red">*</span></label>
                        <select id="positionSelect" name="positionSelect" class="form-select form-select-lg mb-3" disabled required>
                            <option value="">Wybierz stanowisko</option>
                            <!-- Opcje wyboru zaciągane z tabeli JS w zależności od wybranego działu. -->
                        </select>
                    </div>
                    <div class="form-group top-margin">
                        <label class="form-label">Problem <span style="color:red">*</span></label>
                        <select id="problemSelect" name="problemSelect" class="form-select form-select-lg mb-3" disabled required>
                            <option value="">Wybierz problem</option>
                            <!-- Opcje wyboru zaciągane z tabeli JS w zależności od wybranego działu. -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Wiadomość (max 500 znaków) (opcjonalnie)</label><br/>
                        <textarea class="form-control" name="message" maxlength="500"></textarea>
                    </div>
                    <div class="form-group top-margin">
                        <label class="form-label">Załącznik (max 5MB) (opcjonalnie)</label><br/>
                        <input type="file" class="form-control" id="attachment" name="attachment" aria-label="Upload">
<!--
                        <div class="dropzone" id="myDropzone">
                            <div class="dz-message" data-dz-message><span>Kliknij lub upuść plik tutaj.</span></div>
                        </div>
-->
                    </div>
                    <div class="form-group top-margin">
                        <input id="submit" name="submit" class="btn btn-lg btn-primary" type="Submit" disabled/>
                    </div>
                </div>
                <div class="col-5 ie11-margin">
                    <div class="form-group">
                        <label class="form-label">Priorytet (opcjonalnie)</label>
                        <select id="prioritySelect" name="prioritySelect" class="form-select form-select-lg mb-3">
                            <option value="0">Powiadomienie</option>
                            <option value="2" default selected>Standardowy</option>
                            <option value="4">Krytyczny</option>
                        </select>
                    </div>
                    <div id="info" class="form-group alert alert-info text-center">
                        <table class="table">
                            <thead>
                                <td>Priorytet</td>
                                <td>Skutek</td>
                            </thead>
                            <tr>
                                <td>Powiadomienie</td>
                                <td>Uwagi, pomysły, usprawnienia, modyfikacje, utrudnienia, itd.</td>
                            </tr>
                            <tr>
                                <td>Standardowy</td>
                                <td>PRODUKCJA NIE JEST ZAGROŻONA - Usterka powoduje znaczne utrudnienia dla procesu produkcyjnego.</td>
                            </tr>
                            <tr>
                                <td>Krytyczny</td>
                                <td>PRODUKCJA ZATRZYMANA LUB WYSOKIE RYZYKO ZATRZYMANIA - Interwencja musi być podjęta najszybciej jak to tylko możliwe.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
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
                                $('#positionSelect').append('<option>Wybierz stanowisko</option>');
                                $.each(positionData, function(key, value) {
                                    $('#positionSelect').append('<option value="'+ value['position_name'] +'">'+ value['position_name'] +'</option>');
                                });
                            }
                        });
                    }else{
                        $('#positionSelect').empty();
                        $('#positionSelect').attr('disabled', 'disabled');
                        $('#positionSelect').append('<option value="null">Wybierz stanowisko</option>');
                        $('#problemSelect').empty();
                        $('#problemSelect').attr('disabled', 'disabled');
                        $('#problemSelect').append('<option value="null">Wybierz problem</option>');
                        $('#submit').prop("disabled", true);
                    }
                });

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
                                $('#problemSelect').append('<option value="null">Wybierz problem</option>');
                                $.each(problemData, function(key, value) {
                                    $('#problemSelect').append('<option value="'+ value['problem_name'] +'">'+ value['problem_name'] +'</option>');
                                });
                            }
                        });
                    }else{
                        $('#problemSelect').empty();
                        $('#problemSelect').attr('disabled', 'disabled');
                        $('#problemSelect').append('<option value="null">Wybierz problem</option>');
                        $('#submit').prop("disabled", true);
                    }
                });

                $('#problemSelect').on('change', function() {
                    var problemName = $(this).val();
                    if(problemName != "null" && problemName != "Wybierz problem") {
                        $('#submit').removeAttr('disabled', 'disabled');
                    }else{
                        $('#submit').prop("disabled", true);
                    }
                });
            });

/*
//Paste file to dropzone box

var input = document.querySelector("#text");
input.addEventListener("paste",function(event){
    var items = (event.clipboardData || event.originalEvent.clipboardData).items;
    for (index in items) {
        var item = items[index];
        if (item.kind === 'file') {
            var blob = item.getAsFile();
            var reader = new FileReader();
            reader.onload = function(event){
                let img = document.createElement('img')
                img.src = event.target.result
                document.getElementById('gallery').appendChild(img);
            };
            reader.readAsDataURL(blob);
        }
    }
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

var deleteImageBtn = $('#delete-image');
deleteImageBtn.click(function(){
    var image = $('#gallery').children().first().remove();
    $('.uploadIcon').css('display','block');
    dropArea.style.height = '300px';
});

function dataURLtoFile(dataurl, filename) {
    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, {type:mime});
}
*/

/*
Dropzone.options.myDropzone= {
    url: 'ajax/upload',
    autoProcessQueue: true,
    uploadMultiple: false,
    parallelUploads: 1,
    maxFiles: 1,
    maxFilesize: 5,
    dictDefaultMessage: "Drop files here to upd",
    //acceptedFiles: 'image/*',
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
    },
    init: function() {
        dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

        // for Dropzone to process the queue (instead of default form behavior):
        document.getElementById("submit").addEventListener("click", function(e) {
            // Make sure that the form isn't actually being sent.
            e.preventDefault();
            e.stopPropagation();
            dzClosure.processQueue();
        });
    }
}*/
        </script>
@endsection

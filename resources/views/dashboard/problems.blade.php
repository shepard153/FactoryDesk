@extends('dashboard/dashboard_template')
 
 @section('title', 'RUGDesk')
  
 @section('sidebar')
     @parent
  
 @endsection
  
 @section('content')
    <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
        <input type="button" id="createProblem" class="btn btn-success" style="float:right; margin: 0.7vw 1vw 0vw 0vw;" value="Utwórz nowy problem"/>
        <p class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">Zarządzaj problemami</p>
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
        <table class="table table-striped table-hover responsive table align-middle">
            @if ($problems != null)
                <thead>               
                    <tr>
                        <td><b>Kolejność wyświetlania</b></td>
                        <td><b>Nazwa problemu</b></td>
                        <td><b>Obszary zawierające dany problem</b></td>
                        <td><b>Działy zawierające dany problem</b></td>
                        <td><b>Operacje</b></td>
                    </tr>
                </thead>
                <form method="post" action="{{ url('addProblemAction') }}">
                    @csrf
                    <tr id="newProblemForm" style="display: none">
                        <td><input class="form-control" type="text" name="lp"/></td>
                        <td><input class="form-control" type="text" name="problem_name"/></td>
                        <td><input class="form-control" type="text" name="positions_list"/></td>
                        <td><input class="form-control" type="text" name="departments_list"/></td>
                        <td><button class="btn btn-primary" name="create" id="create" value="create">Utwórz</button></td>
                    </tr>
                </form>
                @foreach($problems as $problem)
                    <tr>
                        <form method="post" action="{{ url('editProblemAction') }}">
                            @csrf
                            <td style="width: 5%"><input class="form-control" type="text" name="lp" value="{{ $problem->lp }}" id="{{ $problem->problemID }}" disabled/></td>
                            <td style="width: 15%"><input class="form-control" type="text" name="problem_name" value="{{ $problem->problem_name }}" id="name-{{ $problem->problemID }}" disabled/></td>
                            <td style="width: 45%"><input class="form-control" type="text" name="positions_list" value="{{ $problem->positions_list }}" id="list-{{ $problem->problemID }}" disabled/></td>
                            <td style="width: 20%"><input class="form-control" type="text" name="departments_list" value="{{ $problem->departments_list }}" id="depList-{{ $problem->problemID }}" disabled/></td>
                            <td style="width: 15%">
                                <input type="button" class="btn btn-success" name="edit" id="edit-{{ $problem->problemID }}" data-id="{{ $problem->problemID }}" value="Edytuj"/>
                                <button class="btn btn-success" name="save" id="save-{{ $problem->problemID }}" value="{{ $problem->problemID }}" style="display: none">Zapisz</button>
                                <input type="button" class="btn btn-danger" name="delete" data-bs-toggle="modal" data-bs-target="#modal" data-name="{{ $problem->problem_name }}" data-id="{{ $problem->problemID }}" value="Usuń"/>
                            </td>
                        </form>

                            <!-- Okienko z potwierdzeniem -->
                            <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <form method="post" action="{{ url('deleteProblemAction') }}">
                                    @csrf
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Usuń stanowisko</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p id="text"><!-- Tekst ze skryptu JS --></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                                <button type="Submit" id="confirmDelete" name="confirmDelete" class="btn btn-danger">Potwierdź</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                    </tr>
                @endforeach
            @else
                <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>
            @endif
        </table>
    </div>
    <script>
        $("[name=delete]").click(function() {
            let buttonDelete = $(this).attr('data-name');
            let problemID = $(this).attr('data-id');
            $('#text').text("Czy na pewno chcesz usunąć problem " + buttonDelete + "?");
            document.getElementById("confirmDelete").value = problemID;
        });

        let editButton = [];
        $("[name=edit]").click(function() {
            if (editButton[0] != null){
                $('#' + editButton[0]).prop("disabled", true);
                $('#name-' + editButton[0]).prop("disabled", true);
                $('#list-' + editButton[0]).prop("disabled", true);
                $('#depList-' + editButton[0]).prop("disabled", true);
                $('#save-' + editButton[0]).hide();
                $('#edit-' + editButton[0]).show();
                editButton.shift();
            }

            editButton.push($(this).attr('data-id'));
            $('#' + editButton[0]).removeAttr('disabled');
            $('#name-' + editButton[0]).removeAttr('disabled');
            $('#list-' + editButton[0]).removeAttr('disabled');
            $('#depList-' + editButton[0]).removeAttr('disabled');
            $(this).hide();
            $('#save-' + editButton[0]).show();
        });

        $("#createProblem").click(function() {
            $('#newProblemForm').show();
        });
    </script>
@endsection
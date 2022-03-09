@extends('dashboard/dashboard_template')
 
 @section('title', 'RUGDesk')
  
 @section('sidebar')
     @parent
  
 @endsection
  
 @section('content')
        <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
            <a href="{{ url('addDepartment') }}" class="btn btn-success btn-sm" style="float:right; margin: 0.7vw 1vw 0vw 0vw;">Utwórz dział</a>
            <p class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">Zarządzaj działami</p>
            @if (session('message'))
				<div class="alert alert-danger">{{ session('message') }}</div>
            @endif
            <table class="table table-striped table-hover responsive table align-middle">
                @if ($departments != null)
                    <thead>               
                        <tr>
                            <td></td>
                            <td><b>Nazwa działu</b></td>
                            <td><b>Operacje</b></td>
                        </tr>
                    </thead>
                    @foreach($departments as $department)
                        <tr>
                            <td style="background-image: url({{ 'public/storage/'.$department->image_path }}); background-repeat:no-repeat;background-size:40px 47px; width:40px; height:40px;"></td>
                            <td style="width: 75%">{{ $department->department_name }}</td>
                            <td>
                                <a href="{{ url('department/'.$department->departmentID) }}" class="btn btn-success btn-sm">Edytuj</a>
                                <button class="btn btn-danger btn-sm" name="delete" data-bs-toggle="modal" data-bs-target="#modal" value="{{ $department->department_name }}" data-id="{{ $department->departmentID }}">Usuń</button>

                                <!-- Okienko z potwierdzeniem -->
                                <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <form method="post" action="{{ url('deleteDepartmentAction') }}">
                                        @csrf
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Usuń dział</h5>
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

                            </td>
                        </tr>
                    @endforeach
                @else
                    <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>
                @endif
            </table>
        </div>
    <script>
        $("[name=delete]").click(function() {
            var buttonDelete = $(this).val();
            var departmentID = $(this).attr('data-id');
            $('#text').text("Czy na pewno chcesz usunąć dział " + buttonDelete + "?");
            document.getElementById("confirmDelete").value = departmentID;
        });
    </script>
@endsection
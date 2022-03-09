@extends('dashboard/dashboard_template')
 
 @section('title', 'RUGDesk')
  
 @section('sidebar')
     @parent
  
 @endsection
  
 @section('content')
        <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
            <a href="{{ url('addMember') }}" class="btn btn-success btn-sm" style="float:right; margin: 0.7vw 1vw 0vw 0vw;">Dodaj użytkownika</a>
            <p class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">Zarządzaj użytkownikami</p>
            @if (session('message'))
				<div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <table class="table table-striped table-hover responsive">
                @if ($staffMembers != null)
                <thead>               
                    <tr>
                        <td><b>Login</b></td>
                        <td><b>Nazwa użytkownika</b></td>
                        <td><b>Adres e-mail</b></td>
                        <td><b>Dział</b></td>
                        <td><b>Administrator</b</td>
                        <td><b>Operacje</b></td>
                    </tr>
                </thead>
                    @foreach($staffMembers as $member)
                        @if ($member->login != 'root')
                <tr>
                    <td>{{ $member->login }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->department }}</td>
                    <td>{{ $member->isAdmin == 1 ? 'Tak' : 'Nie' }}</td>
                    <td>
                        <a href="{{ url('staff/'.$member->staffID) }}" class="btn btn-success btn-sm">Edytuj</a>
                        <button class="btn btn-danger btn-sm" name="delete" data-bs-toggle="modal" data-bs-target="#modal" value="{{ $member->login }}" data-id="{{ $member->staffID }}">Usuń</button>

                        <!-- Okienko z potwierdzeniem -->
                        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <form method="post" action="{{ url('deleteMemberAction') }}">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Usuń użytkownika</h5>
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
                        @endif
                    @endforeach
                @else
                    <p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>
                @endif
            </table>
        </div>
    <script>
        $("[name=delete]").click(function() {
            var buttonDelete = $(this).val();
            var staffID = $(this).attr('data-id');
            $('#text').text("Czy na pewno chcesz skasować konto " + buttonDelete + " o numerze ID " + staffID + "?");
            document.getElementById("confirmDelete").value = staffID;
            console.log(staffID);
        });
    </script>
@endsection
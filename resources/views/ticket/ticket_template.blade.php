<HTML>
    <head>
        <meta charset="UTF-8" lang="pl"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/img/favicon-32x32.png') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}"/>
        <script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
        <script nomodule>window.MSInputMethodContext && document.documentMode && document.write('<link rel="stylesheet" href="assets/css/bootstrap-ie11.min.css"><script src="assets/js/element-qsa-scope@1.js"><\/script>');</script>
        <style type="text/css">
            _:-ms-fullscreen, :root .col { flex: 1 0 auto; } /* Poprawka dla IE11. Bez tego, przeglądarka ustawia domyślną szerokość pól na 1% */
            .top-margin{margin-top: 1vw;}
            @media all and (-ms-high-contrast:none)
            {
            *::-ms-backdrop, .ie11-margin { margin-left: 1vw;}
            }
            select::-ms-expand {
                display: none;
            }
            button{
                margin-left: 2%;
            }
            .alternate{
                background-color: #99d0e0;
                color: white;
                font: 48px Impact;
                text-align: center;
                height: 100%;
                display: flex;
                align-items: center;
            }
        </style>
        <title>RUGDesk</title>
    </head>
    <body>
        @section('navbar')
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <a class="navbar-brand" href="index.php">Menu</a>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Zgłoś problem</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('dashboard/') }}">Panel admina</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @show
        <div class="container">
            <div class="row justify-content-md-center top-margin">
                @yield('content')
            </div>
        </div>
    </body>
</HTML>

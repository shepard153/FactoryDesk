<HTML>
    <head>
        <meta charset="UTF-8" lang="pl"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/img/favicon-32x32.png') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/fontawesome6/css/all.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/dropzone.min.css') }}" type="text/css" />
        <script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/js/dropzone.min.js') }}"></script>
        <script src="{{ asset('public/js/chart.min.js') }}"></script>
        <script nomodule>window.MSInputMethodContext && document.documentMode && document.write('<link rel="stylesheet" href="{{ asset('public/css/bootstrap-ie11.min.css') }}"><script src="{{ asset('public/js/element-qsa-scope@1.js') }}"><\/script>');</script>
        <style type="text/css">
            _:-ms-fullscreen, :root .col { flex: 1 0 auto; } /* Poprawka dla IE11. Bez tego, przeglądarka ustawia domyślną szerokość pól na 1% */
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
                width: 250px;
                height: 250px;
            }
            .dropzone {
                position: relative;
                border: 2px solid #ccc;
                overflow: hidden;
                margin-bottom: 10px;
                text-align: center;
                font-family: "Quicksand", sans-serif;
                font-weight: 500;
                font-size: 20px;
                cursor: pointer;
                color: #cccccc;
                border: 4px dashed #009578;
                border-radius: 10px;
            }
            .dropzone:hover {
                border-style: solid;
            }
        </style>
        <title>RUGDesk</title>
    </head>
    <body>
        @section('navbar')
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <img class="img-fluid" src="{{ asset('public/img/carrier-logo.png') }}" width="130px" style="margin-left: 5%;"/>
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav" style="font-size: 18px">
                        <li class="nav-item">
                            <a class="nav-link {{ \Route::currentRouteName() == 'home' ? 'active' : '' }}" aria-current="page" href="{{ url('/') }}">Zgłoś problem</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('dashboard/') }}">Panel admina</a>
                        </li>
                    </ul>
                </div>
            </div>
            <img class="img-fluid" src="{{ asset('public/img/rugdesk-logo.png') }}" width="170px" style="margin-right: 5%"/>
        </nav>
        @show
        <div class="container">
            <div class="row justify-content-md-center mt-2">
                @yield('content')
            </div>
            <footer id="footer" class="flex-wrap justify-content-between align-items-center border-top">
                <div class="col d-flex align-items-center" style="margin: 5px 0px 0px 0px; justify-content: center;">
                    <img src="{{ asset('public/img/favicon-32x32.png') }}" style="margin: 0px 5px -10px 5px;">
                    <span class="text-muted">&copy; 2022 RUG ICT</span>
                </div>
            </footer>
        </div>
    </body>
    <script>
    $(document).ready(function() {
        setInterval(function() {
            var docHeight = $(window).height();
            var footerHeight = $('#footer').height();
            var footerTop = $('#footer').position().top + footerHeight;
            var marginTop = (docHeight - footerTop - 10);

            if (footerTop < docHeight)
                $('#footer').css('margin-top', marginTop + 'px');
            else
                $('#footer').css('margin-top', '-4');
        }, 2);
    });
    </script>
</HTML>

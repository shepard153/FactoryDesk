<HTML>
    <head>
        <meta charset="UTF-8" lang="pl"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/img/favicon-32x32.png') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/fontawesome6/css/all.css') }}">
<!-- Dropzone
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
-->
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
                width: 250px;
                height: 250px;
            }
/*
#drop-area {
    position: relative;
	border: 2px solid #ccc;
    height: 300px;
    max-height: 300px;
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
#drop-area:hover {
  border-style: solid;
}
#drop-area .control-panel {
    position: absolute;
    bottom: 0;
    right: 0;
}
#drop-area .control-panel .item {
    display: inline-block;
    padding: 10px;
    cursor: pointer;
    background-color: #23232360;
}
#drop-area .control-panel .item i {
    color: #fff;
}
#drop-area .uploadIcon {
	border: none;
    height: 100%;
}
#drop-area .uploadIcon i {
    margin: 0 auto;
    display: block;
    width: 60px;
    position: relative;
    top: 45%;
}
#drop-area.highlight {
	border-color: purple;
}
#gallery {
    height: 100%;
}
#gallery img {
	vertical-align: middle;
    width: 100%;
}
#fileElem {
	display: none;
}
*/
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
                            <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Zgłoś problem</a>
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
            <div class="row justify-content-md-center top-margin">
                @yield('content')
            </div>
            <footer id="footer" class="flex-wrap justify-content-between align-items-center border-top" style="margin: 0px -12px 0px -1%">
                <div class="col d-flex align-items-center" style="margin: 5px 0px 0px 0px; justify-content: center;">
                    <img src="{{ asset('public/img/favicon-32x32.png') }}" style="margin: 0px 5px -10px 5px;">
                    <span class="text-muted">&copy; 2022 RUG ICT</span>
                </div>
            </footer>
        </div>
    </body>
    <script>
        $(document).ready(function() {
            var docHeight = $(window).height();
            var footerHeight = $('#footer').height();
            var footerTop = $('#footer').position().top + footerHeight;
            var marginTop = (docHeight - footerTop - 20);

            if (footerTop < docHeight)
                $('#footer').css('margin-top', marginTop + 'px');
            else
                $('#footer').css('margin-top', '0px');
        });
    </script>
</HTML>

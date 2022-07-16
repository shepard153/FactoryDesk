<HTML>
  <head>
    <meta charset="UTF-8" lang="pl"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('fontawesome6/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}" type="text/css" />
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script nomodule>window.MSInputMethodContext && document.documentMode && document.write('<link rel="stylesheet" href="{{ asset('css/bootstrap-ie11.min.css') }}"><script src="{{ asset('js/element-qsa-scope@1.js') }}"><\/script>');</script>
    <style type="text/css">
      _:-ms-fullscreen, :root .col { flex: 1 0 auto; } /* IE11 workaround. Without this, browser sets input fields width to 1% */
      @media all and (-ms-high-contrast:none){
        *::-ms-backdrop, .ie11-margin { margin-left: 1vw;}
      }
      @media only screen and (max-width: 991px){
        #rugLogo {display: none}
        #navbarNav {text-align: right}
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
        <div class="container-fluid">
          <button id="navbarToggler" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav" style="font-size: 18px">
              <li class="nav-item">
                <a class="nav-link {{ \Route::currentRouteName() == 'home' ? 'active' : '' }}" aria-current="page" href="{{ url('/') }}">{{ __('main_page.raise_issue') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ \Route::currentRouteName() == 'overview' ? 'active' : '' }}" aria-current="page" href="{{ url('overview') }}">{{ __('main_page.overview') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ url('dashboard/') }}">{{ __('main_page.dashboard') }}</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      @show
      <div class="container">
        @yield('content')
        <footer id="footer" class="row border-top">
          <div class="col" style="margin: 5px 0px 0px 0px; justify-content: center;">
            <span class="text-muted">&copy; 2022 Kamil Kośmider</span>
          </div>
          <div class="col">
            <ul class="nav justify-content-end">
              <li class="nav-item"><a class="nav-link px-2 text-muted" href="{{ url('lang/pl') }}">Polski</a></li>
              <li class="nav-item"><a class="nav-link px-2 text-muted" href="{{ url('lang/en') }}">English</a></li>
            </ul>
          </div>
        </footer>
      </div>
    </body>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" async="async"></script>
    <script>
    $(document).ready(function() {
        setInterval(function() {
            var docHeight = $(window).height();
            var footerHeight = $('#footer').outerHeight();
            var footerTop = $('#footer').position().top + footerHeight;
            var marginTop = (docHeight - footerTop - 10);

            if (footerTop < docHeight)
                $('#footer').css('margin-top', marginTop + 'px');
            else
                $('#footer').css('margin-top', '-4');
        }, 20);
    });
    </script>
</HTML>

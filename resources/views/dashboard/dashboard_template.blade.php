<HTML>
    <head>
        <meta charset="UTF-8" lang="pl">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=11" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/img/favicon-32x32.png') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/css/sidebars.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/css/lightbox.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/fontawesome6/css/all.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/dropzone.min.css') }}"/>
        <title>RUGDesk - Panel Admina</title>
        <script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('public/js/lightbox.js') }}"></script>
        <script src="{{ asset('public/js/dropzone.min.js') }}"></script>
        <script src="{{ asset('public/js/chart.min.js') }}"></script>
        <style>
          img {
            margin-top: 0.9vw;
            width:75;
            height:75;
          }
          h2{
            float:right;
          }
          @media only screen and (max-width: 1320px) {
                .removable {
                    display: none;
                }
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
    </head>
<body>

@section('sidebar')
<main>
  <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 240px;">
    <p class="d-flex align-items-center mb-md-0 ms-3 me-md-auto text-white text-decoration-none">
    <i class="fa-solid fa-signs-post fs-4"></i> &nbsp;
      <span class="fs-4">Menu</span>
    </p>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li>
        <a href="{{ url('dashboard') }}" class="nav-link text-white">
          <i class="fa-solid fa-gauge"></i> &nbsp;
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ url('tickets/awaiting') }}" class="nav-link text-white">
          <i class="fa-solid fa-check"></i> &nbsp;
          Do akceptacji
        </a>
      </li>
      <li>
        <a href="{{ url('tickets/active') }}" class="nav-link text-white">
          <i class="fa-solid fa-list"></i> &nbsp;
          Zgłoszenia
        </a>
      </li>
      <li>
        <a href="{{ url('my_tickets') }}" class="nav-link text-white">
          <i class="fa-solid fa-clipboard-list"></i> &nbsp;
          Moje zgłoszenia
        </a>
      </li>
      <li>
        <a href="{{ url('reporter') }}" class="nav-link text-white">
          <i class="fa-solid fa-briefcase"></i> &nbsp;
          Raportowanie
        </a>
      </li>
      @if (auth()->user()->isAdmin == 1)
        <li>
          <a href=" {{ url('staff') }}" class="nav-link text-white">
            <i class="fa-solid fa-users"></i> &nbsp;
            Użytkownicy
          </a>
        </li>
        <li>
          <a href="{{ url('departments') }}" class="nav-link text-white">
            <i class="fa-solid fa-building"></i> &nbsp;
            Działy
          </a>
        </li>
        <li>
          <a href="{{ url('formEditor') }}" class="nav-link text-white">
           <i class="fa-brands fa-wpforms"></i> &nbsp;
            Edytor formularza
          </a>
        </li>
      @endif
    </ul>
    <div class="row align-items-end">
      <div class="col">
        <ul class="nav nav-pills flex-column mb-auto">
            @if (auth()->user()->isAdmin == 1)
                <li>
                    <a href="{{ url('settings') }}" class="nav-link text-white">
                        <i class="fa-solid fa-gear"></i> &nbsp;
                        Ustawienia
                    </a>
                </li>
            @endif
          <li>
            <a href="{{ url('/') }}" class="nav-link text-white">
              <i class="fa-solid fa-triangle-exclamation"></i> &nbsp;
              Zgłoś problem
            </a>
          </li>
        </ul>
      </div>
    </div>
    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset('public/img/agent.png') }}" alt="" class="rounded-circle me-2" style="width: 48px; height: 48px; margin-top: 0.1vw">
        @php
            $username = wordwrap(auth()->user()->name, 20, "<br />\n");
            echo "<strong>$username</strong>"
        @endphp
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
        <li>
            <a class="dropdown-item" href="{{ url('profile/staff') }}">
                <i class="fa-solid fa-id-card-clip"></i>&nbsp;
                Mój profil
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="{{ url('logout') }}">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>&nbsp;
                Wyloguj
            </a>
        </li>
      </ul>
    </div>
  </div>

  <div class="b-example-divider">
      <button type="button" id="menuToggler" class="btn btn-dark" style="position: sticky"><i id="navArrow" class="fa-solid fa-angle-left"></i></button>
  </div>
  @show
  <div class="container-fluid" style="background:#F2F2F2; overflow: auto;">
    <div class="col">
        <p class="fs-2 border-bottom" style="background:white; margin: 0 -0.6vw 1vw -0.6vw; padding: 0.5vw 4vw 0.6vw 0vw; text-align: right">{{ $pageTitle }}</p>
        @yield('content')
        <footer id="footer" class="border-top">
            <div class="col d-flex align-items-center" style="justify-content: center;">
                <img src="{{ asset('public/img/favicon-32x32.png') }}" style="margin: 0px 5px -2px 5px; height: 32px; width: 32px">
                <span class="text-muted">&copy; 2022 RUG ICT</span>
            </div>
        </footer>
    </div>
</div>

</main>
</body>
<script src="{{ asset('public/js/bootstrap.bundle.min.js') }}" async="async"></script>
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
        }, 200);
    });

    $('#menuToggler').on('click', function(){
        var sidebar = $('#sidebar');
        if ($('#sidebar').hasClass('d-flex')){
            sidebar.removeClass('d-flex');
            $('#navArrow').removeClass('fa-angle-left').addClass('fa-angle-right');
        }
        else{
            sidebar.addClass('d-flex');
            $('#navArrow').removeClass('fa-angle-right').addClass('fa-angle-left');
        }
        sidebar.toggle(500);
    });
</script>
</html>

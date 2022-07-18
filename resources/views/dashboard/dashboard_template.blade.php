<HTML>
  <head>
    <meta charset="UTF-8" lang="{{ app()->currentLocale() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/sidebars.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}"/>
    <link rel="stylesheet" href="{{ asset('fontawesome6/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}"/>
    <title>{{ __('dashboard_main.title') }}</title>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/lightbox.js') }}"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <style>
      img {
        margin-top: 0.9vw;
        width:75;
        height:75;
      }
      h2{
        float:right;
      }
      @media only screen and (max-width: 1220px) {
        .removable {
          display: none;
        }
        #menuToggler {
          display: none;
        }
        #topNav {
          display: block;
        }
      }
      @media only screen and (min-width: 1220px) {
        #topNav {
          display: none;
        }
      }

      @media only screen and (max-width: 992px) {
        #bigLogo {
          display: none;
        }
      }
      @media only screen and (min-width: 992px) {
        #smallLogo {
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
    <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-2 text-white bg-dark" style="width: 240px; display: none">
      <img id="bigLogo" class="float-start" src="{{ asset('img/factorydesk-logo.png') }}" alt="FactoryDesk Logo" style="width: 100%; height:auto;"/>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li>
          <a href="{{ url('dashboard') }}" class="nav-link text-white">
            <i class="fa-solid fa-gauge"></i> &nbsp;
            {{ __('dashboard_main.dashboard_link') }}
          </a>
        </li>
        <li>
          <a href="{{ url('tickets/awaiting') }}" class="nav-link text-white">
            <i class="fa-solid fa-check"></i> &nbsp;
            {{ __('dashboard_main.tickets_awaiting_link') }}
          </a>
        </li>
        <li>
          <a href="{{ url('tickets/active') }}" class="nav-link text-white">
            <i class="fa-solid fa-list"></i> &nbsp;
            {{ __('dashboard_main.tickets_link') }}
          </a>
        </li>
        <li>
          <a href="{{ url('my_tickets') }}" class="nav-link text-white">
            <i class="fa-solid fa-clipboard-list"></i> &nbsp;
            {{ __('dashboard_main.my_tickets_link') }}
          </a>
        </li>
        <li>
          <a href="{{ url('reporter') }}" class="nav-link text-white">
            <i class="fa-solid fa-briefcase"></i> &nbsp;
            {{ __('dashboard_main.reporting_link') }}
         </a>
        </li>
        @if (auth()->user()->isAdmin == 1)
          <li>
            <a href=" {{ url('staff') }}" class="nav-link text-white">
              <i class="fa-solid fa-users"></i> &nbsp;
              {{ __('dashboard_main.users_link') }}
            </a>
          </li>
          <li>
            <a href="{{ url('departments') }}" class="nav-link text-white">
              <i class="fa-solid fa-building"></i> &nbsp;
              {{ __('dashboard_main.departments_link') }}
            </a>
          </li>
          <li>
            <a href="{{ url('formEditor') }}" class="nav-link text-white">
              <i class="fa-brands fa-wpforms"></i> &nbsp;
              {{ __('dashboard_main.form_editor_link') }}
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
                  {{ __('dashboard_main.settings_link') }}
                </a>
              </li>
            @endif
            <li>
              <a href="{{ url('/') }}" class="nav-link text-white">
                <i class="fa-solid fa-triangle-exclamation"></i> &nbsp;
                {{ __('dashboard_main.raise_issue_link') }}
              </a>
            </li>
          </ul>
        </div>
      </div>
      <hr>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="{{ asset('img/favicon-32x32.png') }}" alt="Profile icon" class="rounded-circle me-2 my-auto" style="width: 48px; height: 48px;">
          @php
            $username = wordwrap(auth()->user()->name, 20, "<br />\n");
            echo "<strong>$username</strong>"
          @endphp
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
          <li>
            <a class="dropdown-item" href="{{ url('profile/staff') }}">
              <i class="fa-solid fa-id-card-clip"></i>&nbsp;
              {{ __('dashboard_main.my_profile_link') }}
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item" href="{{ url('logout') }}">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>&nbsp;
              {{ __('dashboard_main.logout') }}
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="b-example-divider">
    </div>
    @show
    <div class="container-fluid" style="background:#F2F2F2; overflow: auto;">
      <nav id="topNav" class="navbar navbar-expand-lg navbar-dark bg-dark mb-2">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <img id="smallLogo" class="float-start my-auto" src="{{ asset('img/factorydesk-logo.png') }}" alt="FactoryDesk Logo" style="width: 250px; height: 50px"/>
          <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
            <ul class="navbar-nav" style="font-size: 18px">
              <li class="nav-item">
                <a class="nav-link {{ \Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" aria-current="page" href="{{ url('dashboard') }}">{{ __('dashboard_main.dashboard_link') }}</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link {{ \Route::currentRouteName() == 'tickets' ? 'active' : '' }} dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">{{ __('dashboard_main.tickets_link') }}</a>
                <ul class="dropdown-menu bg-dark">
                  <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ url('tickets/awaiting') }}">{{ __('dashboard_main.tickets_awaiting_link') }}</a>
                  </li>
                  <li>
                    <a class="nav-link" aria-current="page" href="{{ url('tickets') }}">{{ __('dashboard_main.tickets_link') }}</a>
                  </li>
                  <li>
                    <a class="nav-link" aria-current="page" href="{{ url('my_tickets') }}">{{ __('dashboard_main.my_tickets_link') }}</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ \Route::currentRouteName() == 'reporter' ? 'active' : '' }}" aria-current="page" href="{{ url('reporter') }}">{{ __('dashboard_main.reporting_link') }}</a>
              </li>
              @if (auth()->user()->isAdmin == 1)
                <li class="nav-item">
                  <a class="nav-link {{ \Route::currentRouteName() == 'staff' ? 'active' : '' }}" aria-current="page" href="{{ url('staff') }}">{{ __('dashboard_main.users_link') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ \Route::currentRouteName() == 'departments' ? 'active' : '' }}" aria-current="page" href="{{ url('departments') }}">{{ __('dashboard_main.departments_link') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ \Route::currentRouteName() == 'formEditor' ? 'active' : '' }}" aria-current="page" href="{{ url('formEditor') }}">{{ __('dashboard_main.form_editor_link') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ \Route::currentRouteName() == 'settings' ? 'active' : '' }}" aria-current="page" href="{{ url('settings') }}">{{ __('dashboard_main.settings_link') }}</a>
                </li>
              @endif
              <li class="nav-item dropdown">
                <a class="nav-link {{ \Route::currentRouteName() == 'profile' ? 'active' : '' }} dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">{{ $username }}</a>
                <ul class="dropdown-menu bg-dark">
                  <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ url('profile/staff') }}">{{ __('dashboard_main.my_profile_link') }}</a>
                  </li>
                  <li>
                    <a class="nav-link" aria-current="page" href="{{ url('logout') }}">{{ __('dashboard_main.logout') }}</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="col">
        <div class="row border-bottom px-3" style="background:white; margin: 0 -0.6vw 1vw -0.6vw;">
          <div class="col">
            <p class="fs-2 pt-2 text-end">{{ $pageTitle }}</p>
          </div>
        </div>
        @yield('content')
        <footer id="footer" class="row border-top">
          <div class="col" style="justify-content: center;">
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
    </div>
  </main>
</body>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" async="async"></script>
<script>
$(document).ready(function() {
    sidebarToggler();

    setInterval(function() {
        var docHeight = $(window).height();
        var footerHeight = $('#footer').outerHeight();
        var footerTop = $('#footer').position().top + footerHeight;
        var marginTop = (docHeight - footerTop - 10);

        if (footerTop < docHeight)
            $('#footer').css('margin-top', marginTop + 'px');
        else
            $('#footer').css('margin-top', '-1');
    }, 200);
});

$(window).resize(function() {
    sidebarToggler();
});

function sidebarToggler() {
    if ($(window).width() < 1220 ) {
        $('#sidebar').removeClass("d-flex");
    }
    else {
        $('#sidebar').addClass("d-flex");
    }
}
</script>
</html>

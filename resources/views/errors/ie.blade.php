<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" lang="pl">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script nomodule>window.MSInputMethodContext && document.documentMode && document.write('<link rel="stylesheet" href="{{ asset('css/bootstrap-ie11.min.css') }}"><script src="{{ asset('js/element-qsa-scope@1.js') }}"><\/script>');</script>
    <title>RIP IE</title>
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      html,body {
        height: 100%;
      }

      body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
      }

      .form-signin .checkbox {
        font-weight: 400;
      }

      .form-signin .form-floating:focus-within {
        z-index: 2;
      }

      .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }

      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
    </style>
  </head>
  <body class="text-center bg-dark">
    <div class="col">
        <img class="mb-4" src="{{ asset('img/ie.png') }}" alt="" width="200" height="250">
        <h3 style="color:white;">{{ __('error_pages.ie_error') }}</h3><br/>
        <a href="https://www.mozilla.org/firefox/new/"><img class="mb-4" src="{{ asset('img/firefox.png') }}" alt="" width="260" height="100"></a>
        <a href="https://www.opera.com/download"><img class="mb-4" src="{{ asset('img/opera.png') }}" alt="" width="280" height="100" style="margin: 0vw 3vw 0vw 3vw;"></a>
        <a href="https://www.google.com/chrome/"><img class="mb-4" src="{{ asset('img/chrome.png') }}" alt="" width="300" height="100"></a>
        <a href="microsoft-edge:{{ url('login') }}"><img class="mb-4" src="{{ asset('img/edge.png') }}" alt="" width="320" height="110"></a>
    </div>
  </body>
</html>

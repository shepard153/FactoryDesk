<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" lang="pl">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/img/favicon-32x32.png') }}" />
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}"/>
    <script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
    <title>Logowanie</title>
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
  <body class="text-center">
    <main class="form-signin">
      <form action="{{ route('loginAction') }}" method="post">
        <figure class="figure" style="margin-left: -7vw">
          <img class="mb-4" src="{{ asset('public/img/rugdesk-logo.png') }}" alt="" width="560" height="120">
          <figcaption class="figure-caption text-middle" style="margin-top: -2.5vw">RUG TICKETING SYSTEM</figcaption>
        </figure>
        <h1 class="h3 mb-3 fw-normal">Logowanie</h1>
        @csrf
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" name="login" placeholder="Login" required/>
          <label for="floatingInput">Login lub email</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Hasło" required/>
          <label for="floatingPassword">Hasło</label>
        </div>
          @if ($errors->has('error'))
              <div class="alert alert-danger">{{ $errors->first('error') }}</div>
          @endif
        <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Zaloguj</button>
      </form>
    </main>
  </body>
</html>

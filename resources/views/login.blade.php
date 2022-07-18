<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" lang="pl">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <title>{{ __('login.title') }}</title>
    <style>
      @media (max-width: 768px) {
        #bigLogo{
          display: none;
        }
      }

      @media (min-width: 768px) {
        #smallLogo{
          display: none;
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

      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
    </style>
  </head>
  <body class="container text-center text-warning bg-dark">
    <div class="row" id="bigLogo">
      <img class="mb-2" src="{{ asset('img/factorydesk-logo.png') }}" alt="FactoryDesk Logo">
    </div>
    <main class="form-signin">
      <img class="img-fluid mb-2" id="smallLogo" src="{{ asset('img/factorydesk-logo.png') }}" alt="FactoryDesk Logo">
      <form action="{{ route('loginAction') }}" method="post">
        <h1 class="h3 mb-3 fw-normal">{{ __('login.title') }}</h1>
        @csrf
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" name="login" placeholder="Login" required/>
          <label for="floatingInput" class="text-dark">{{ __('login.login_input') }}</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="{{ __('login.password_input') }}" required/>
          <label for="floatingPassword" class="text-dark">{{ __('login.password_input') }}</label>
        </div>
          @if ($errors->has('error'))
              <div class="alert alert-danger">{{ $errors->first('error') }}</div>
          @endif
        <button class="w-100 btn btn-lg btn-warning mb-2" type="submit" name="submit">{{ __('login.signin') }}</button>
      </form>
      <a class="link-light" href="{{ url('/')}}">{{ __('login.return_main') }}</a>
      <ul class="nav justify-content-evenly border-top border-secondary mt-3">
        <li class="nav-item"><a class="nav-link ps-2 text-warning" href="{{ url('lang/pl') }}">Polski</a></li>
        <li class="nav-item"><a class="nav-link px-2 text-warning" href="{{ url('lang/en') }}">English</a></li>
      </ul>
    </main>
  </body>
</html>

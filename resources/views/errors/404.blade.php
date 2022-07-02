<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8">
    <title>RUGDesk - 404</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/error-500-style.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <style type="text/css">
      .blink {
        animation: blink 1s step-start 0s infinite;
        -webkit-animation: blink 1s step-start 0s infinite;
      }
    </style>
  </head>
  <body>
    <h5>{{ __('error_pages.not_found') }}</h5>
    <h1>4</h1>
    <h1 class="blink">04</h1>
    <div class="box">
	  <span></span><span></span>
	  <span></span><span></span>
	  <span></span>
	</div>
    <div class="box" style="margin-left: -17%">
	</div>
    <p>{!! __('error_pages.not_found_desc') !!}</p>
  </body>
</html>

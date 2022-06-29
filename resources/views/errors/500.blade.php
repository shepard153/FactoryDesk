<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta charset="UTF-8">
    <title>RUGDesk - 500</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('public/css/error-500-style.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/img/favicon-32x32.png') }}">
  </head>
  <body>
    @php
	  error_reporting(-1); // reports all errors
	  ini_set("display_errors", "1"); // shows all errors
	  ini_set("log_errors", 1);
	  ini_set("error_log", "log/php-error.log");
    @endphp
    <h5>{{ __('error_pages.internal') }}</h5>
    <h1>5</h1>
    <h1>00</h1>
    <div class="box">
	  <span></span><span></span>
	  <span></span><span></span>
	  <span></span>
	</div>
    <div class="box">
	  <span></span><span></span>
	  <span></span><span></span>
	  <span></span>
	</div>
    <p> {!! __('error_pages.internal_desc') !!}</p>
  </body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->currentLocale() }}" >
  <head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - 500</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/error-500-style.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
  </head>
  <body>
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

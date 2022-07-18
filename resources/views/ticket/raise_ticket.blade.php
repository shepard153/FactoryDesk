@extends('ticket/ticket_template')

@section('navbar')
    @parent

@endsection

@section('content')
  <p class="fs-4 mt-3 border-bottom text-center">{{ __('raise_ticket_form.select_department') }}</p>
  <div class="row justify-content-md-center mt-1">
    @php
      $i = 1
    @endphp
    @foreach ($departments as $department)
      @continue ($department->isHidden == 1)
      <div class='col col-lg-3 mt-1 mx-2'>
        <a href="{{ url('ticket_step2/'.$department->department_name) }}" style="text-decoration:none">
          @if ($department->image_path == null)
            <div class="rounded alternate">{{ $department->department_name }}</div>
          @else
            <img src="{{ url($department->image_path) }}" class='rounded' width='250' height='250' alt='{{ $department->department_name }}'>
          @endif
        </a>
      </div>
      @if ($i % 3 == 0)
        </div><div class="row justify-content-md-center mt-4">
      @endif
      @php
        $i++
      @endphp
    @endforeach
  </div>
@endsection

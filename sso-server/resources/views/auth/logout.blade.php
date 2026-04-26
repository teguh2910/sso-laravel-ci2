@extends('layout')
@section('title','Signing out...')
@section('content')
  <h1>Signing out…</h1>
  <p class="muted">Notifying connected applications.</p>

  {{-- Hidden iframes hit each client's local-logout URL, clearing their session cookie --}}
  <div style="position:absolute;left:-9999px;top:-9999px;width:0;height:0;overflow:hidden">
    @foreach($clients as $url)
      <iframe src="{{ $url }}" width="0" height="0" frameborder="0"></iframe>
    @endforeach
  </div>

  <noscript>
    <p>Please <a href="{{ route('login') }}">click here</a> to continue.</p>
  </noscript>

  <script>
    // Give iframes a moment to clear sessions, then redirect to login.
    setTimeout(function () {
      window.location.href = "{{ route('login') }}";
    }, 1200);
  </script>
@endsection

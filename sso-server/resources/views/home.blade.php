@extends('layout')
@section('title','SSO Server')
@section('content')
  <h1>SSO Server</h1>
  @auth
    <p>Signed in as <strong>{{ auth()->user()->email }}</strong>.</p>
    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
  @else
    <p>You are not signed in.</p>
    <a href="{{ route('login') }}"><button type="button">Go to login</button></a>
  @endauth
@endsection

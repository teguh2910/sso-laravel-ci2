@extends('layout')
@section('title','Sign in - SSO')
@section('content')
  <h1>Sign in</h1>
  @if ($errors->any())
    <div class="err">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('login') }}">
    @csrf
    <input type="hidden" name="redirect" value="{{ $redirect ?? request('redirect') }}">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email','demo@example.com') }}" required autofocus>
    <label>Password</label>
    <input type="password" name="password" value="password" required>
    <button type="submit">Sign in</button>
  </form>
  <p class="muted">Demo: demo@example.com / password</p>
@endsection

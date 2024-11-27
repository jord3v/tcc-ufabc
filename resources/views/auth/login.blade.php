@extends('layouts.guest-app')

@section('content')
<div class="container container-normal py-4">
    <div class="row align-items-center g-4">
      <div class="col-lg">
        <div class="container-tight">
          <div class="text-center mb-4">
            <a href="{{route('dashboard')}}" class="navbar-brand navbar-brand-autodark"><img src="{{ asset('img/logo.svg') }}" height="72" alt=""></a>
          </div>
          <div class="card card-md">
            <div class="card-body">
              <h2 class="h2 text-center mb-4">{{ __('Login') }}</h2>
              <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate="">
                  @csrf
                <div class="mb-3">
                  <label class="form-label">{{ __('Email Address') }}</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="mb-2">
                  <label class="form-label">
                      {{ __('Senha') }}
                      <span class="form-label-description">
                          <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                      </span>
                  </label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                </div>
                <div class="mb-2">
                  <label class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                      <span class="form-check-label">{{ __('Remember Me') }}</span>
                  </label>
                </div>
                <div class="form-footer">
                  <button type="submit" class="btn btn-primary w-100">
                      {{ __('Login') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg d-none d-lg-block">
        <img src="{{asset('img/undraw_secure_login_pdn4.svg')}}" height="300" class="d-block mx-auto" alt="">
      </div>
    </div>
</div>
@endsection
@extends('layouts.guest-app')

@section('content')
<div class="container container-normal py-4">
    <div class="row align-items-center g-4">
      <div class="col-lg">
        <div class="container-tight">
          <div class="text-center mb-4">
            <a href="{{route('dashboard')}}" class="navbar-brand navbar-brand-autodark"><img src="{{ asset('img/logo.svg') }}" height="36" alt=""></a>
          </div>
          <div class="card card-md">
            <div class="card-body">
              <h2 class="h2 text-center mb-4">{{ __('Reset Password') }}</h2>
                <form method="POST" action="{{ route('password.update') }}" autocomplete="off" novalidate="">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                  <label class="form-label">{{ __('Email Address') }}</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="mb-2">
                  <label class="form-label">{{ __('Senha') }}</label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ __('Confirmar senha') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
                <div class="form-footer">
                  <button type="submit" class="btn btn-primary w-100">
                        {{ __('Reset Password') }}
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
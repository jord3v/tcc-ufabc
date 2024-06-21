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
              <h2 class="h2 text-center mb-4">{{ __('Register') }}</h2>
              <form method="POST" action="{{ route('register') }}" autocomplete="off" novalidate="">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                <div class="mb-2">
                  <label class="form-label">{{ __('Email Address') }}</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                    {{ __('Register') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
          <div class="text-center text-secondary mt-3">
            <a href="{{ route('login') }}" tabindex="-1">{{ __('Already registered?') }}</a>
          </div>
        </div>
      </div>
      <div class="col-lg d-none d-lg-block">
        <img src="{{asset('img/undraw_secure_login_pdn4.svg')}}" height="300" class="d-block mx-auto" alt="">
      </div>
    </div>
</div>
@endsection
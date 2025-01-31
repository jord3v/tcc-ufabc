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
              <h2 class="h2 text-center mb-4">{{ __('Reset Password') }}</h2>
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
              @endif
              <form method="POST" action="{{ route('password.email') }}" autocomplete="off" novalidate="">
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
                <div class="form-footer">
                  <button type="submit" class="btn btn-primary w-100">
                    {{ __('Send Password Reset Link') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg d-none d-lg-block">
        <img src="{{asset('img/undraw_contract_upwc.svg')}}" height="550" class="d-block mx-auto" alt="">
      </div>
    </div>
</div>
@endsection
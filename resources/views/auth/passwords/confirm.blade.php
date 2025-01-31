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
                  <h2 class="h2 text-center mb-4">{{ __('Confirmar senha') }}</h2>
                  {{ __('Please confirm your password before continuing.') }}
                  <form method="POST" action="{{ route('password.confirm') }}">
                     @csrf
                     <div class="card-body text-center">
                        <div class="mb-4">
                           <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                           @error('password')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                        <div>
                           <button type="submit" class="btn btn-primary w-100">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z"></path>
                                 <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                 <path d="M8 11v-5a4 4 0 0 1 8 0"></path>
                              </svg>
                              {{ __('Confirmar senha') }}
                           </button>
                           @if (Route::has('password.request'))
                           <a class="btn btn-link" href="{{ route('password.request') }}">
                           {{ __('Forgot Your Password?') }}
                           </a>
                           @endif
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg d-none d-lg-block">
         <img src="{{asset('img/undraw_contract_upwc.svg')}}" height="300" class="d-block mx-auto" alt="">
      </div>
   </div>
</div>
@endsection
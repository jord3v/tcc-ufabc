@extends('layouts.app')
@section('content')
<div class="page-body">
   <div class="container-xl d-flex flex-column justify-content-center">
      <div class="empty">
         @if (session('resent'))
         <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
         </div>
         @endif
         <p class="empty-title">{{ __('Verify Your Email Address') }}</p>
         <p class="empty-subtitle text-secondary">
            {{ __('Before proceeding, please check your email for a verification link.') }}<br>{{ __('If you did not receive the email') }},
         </p>
         <div class="empty-action">
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
               @csrf
               <button type="submit" class="btn btn-link">{{ __('click here to request another') }}</button>.
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
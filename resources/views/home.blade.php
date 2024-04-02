@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">
               page-pretitle
            </div>
            <h2 class="page-title">
               {{ __('Dashboard') }}
            </h2>
         </div>
      </div>
   </div>
</div>
<div class="page-body">
   <div class="container-xl">
      <div class="card card-lg">
         <div class="card-body ">
            <div class="row g-4">
               <div class="col-12">
                  @if (session('status'))
                  <div class="alert alert-success" role="alert">
                     {{ session('status') }}
                  </div>
                  @endif
                  {{ __('You are logged in!') }}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
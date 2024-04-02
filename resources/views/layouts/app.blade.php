<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <!-- Fonts -->
      <link rel="dns-prefetch" href="//fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
      <!-- Scripts -->
      <link rel="stylesheet" href="{{ mix('css/app.css') }}">
   </head>
   <body>
      <div class="page">
         <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">
               <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <h1 class="navbar-brand navbar-brand-autodark">
                  <a href="{{route('dashboard')}}">
                  <img src="{{ asset('img/logo.svg') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
                  </a>
               </h1>
               <div class="navbar-nav flex-row d-lg-none">
                  <div class="nav-item dropdown">
                     <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url('{{auth()->user()->avatar}}')"></span>
                        <div class="d-none d-xl-block ps-2">
                           <div>{{auth()->user()->name}}</div>
                        </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-profile">
                        Meu perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                           @csrf
                        </form>
                     </div>
                  </div>
               </div>
               <div class="collapse navbar-collapse" id="sidebar-menu">
                  <ul class="navbar-nav pt-lg-3">
                     <li class="nav-item {{request()->routeIs('dashboard') ? 'active': ''}}">
                        <a class="nav-link" href="{{route('dashboard')}}">
                           <span class="nav-link-title">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dashboard" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                 <path d="M13.45 11.55l2.05 -2.05"></path>
                                 <path d="M6.4 20a9 9 0 1 1 11.2 0z"></path>
                              </svg>
                              Página inicial
                           </span>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </aside>
         <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
            <div class="container-xl">
               <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="navbar-nav flex-row order-md-last">
                  <div class="nav-item dropdown">
                     <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url('{{auth()->user()->avatar}}')"></span>
                        <div class="d-none d-xl-block ps-2">
                           <div>{{auth()->user()->name}}</div>
                        </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-profile">
                        Meu perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                        </a>
                     </div>
                  </div>
               </div>
               <div class="collapse navbar-collapse" id="navbar-menu">
               </div>
            </div>
         </header>
         <main class="page-wrapper">
            @yield('content')
            <footer class="footer footer-transparent d-print-none">
               <div class="container-xl">
                  <div class="row text-center align-items-center flex-row-reverse">
                     <div class="col-lg-auto ms-lg-auto">
                        <ul class="list-inline list-inline-dots mb-0">
                           <li class="list-inline-item"><a href="https://tabler.io/docs" target="_blank" class="link-secondary" rel="noopener">Documentation</a></li>
                        </ul>
                     </div>
                     <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="list-inline list-inline-dots mb-0">
                           <li class="list-inline-item">
                              Copyright © 2023
                              <a href="." class="link-secondary">Tabler</a>.
                              All rights reserved.
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </footer>
         </main>
      </div>
      <script src="{{ mix('js/app.js') }}" defer></script>
   </body>
</html>
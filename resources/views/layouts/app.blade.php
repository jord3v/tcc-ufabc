@php 
   $user = auth()->user();
@endphp
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
      <style>
         @import url('https://rsms.me/inter/inter.css');
         :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
         }
         body {
            font-feature-settings: "cv03", "cv04", "cv11";
         }
      </style>
      @if (session('download'))
         <meta http-equiv="refresh" content="1;url={{route('payments.download', session('download'))}}">
      @endif
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
                        <span class="avatar avatar-sm" style="background-image: url('{{avatar()}}')"></span>
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
                              Painel administrativo
                           </span>
                        </a>
                     </li>
                     @can('company-list')
                     <li class="nav-item {{request()->routeIs('companies.index') ? 'active': ''}}">
                        <a class="nav-link" href="{{route('companies.index')}}">
                           <span class="nav-link-title">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-building-skyscraper"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M5 21v-14l8 -4v18" /><path d="M19 21v-10l-6 -4" /><path d="M9 9l0 .01" /><path d="M9 12l0 .01" /><path d="M9 15l0 .01" /><path d="M9 18l0 .01" /></svg>
                              Prestadores de serviços
                           </span>
                        </a>
                     </li>
                     @endcan
                     @can('report-list')
                     <li class="nav-item {{request()->routeIs('reports.index') ? 'active': ''}}">
                        <a class="nav-link" href="{{route('reports.index')}}">
                           <span class="nav-link-title">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-description"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                              Relatórios
                           </span>
                        </a>
                     </li>
                     @endcan
                     @can('note-list')
                     <li class="nav-item {{request()->routeIs('notes.index') ? 'active': ''}}">
                        <a class="nav-link" href="{{route('notes.index')}}">
                           <span class="nav-link-title">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-receipt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" /></svg>
                              Notas de empenho
                           </span>
                        </a>
                     </li>
                     @endcan
                     @can('payment-list')
                     <li class="nav-item {{request()->routeIs('payments.index') ? 'active': ''}}">
                        <a class="nav-link" href="{{route('payments.index')}}">
                           <span class="nav-link-title">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                              Pagamentos
                           </span>
                        </a>
                     </li>
                     @endcan
                     @canany(['user-list', 'role-list', 'location-list', 'file-list'])
                     <li class="nav-item {{request()->routeIs(['users.index', 'roles-and-permissions.index', 'locations.index', 'files.index']) ? 'active': ''}} dropdown">
                        <a class="nav-link dropdown-toggle" href="#settings" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                          <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/lifebuoy -->
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-tool"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                          </span>
                          <span class="nav-link-title">
                            Ajustes
                          </span>
                        </a>
                        <div class="dropdown-menu {{request()->routeIs(['users.index', 'roles-and-permissions.index', 'locations.index', 'files.index']) ? 'show': ''}}">
                          @can('user-list')
                          <a class="dropdown-item {{request()->routeIs('users.index') ? 'active': ''}}" href="{{route('users.index')}}">
                            Usuários
                          </a>
                          @endcan     
                          @can('role-list')
                          <a class="dropdown-item {{request()->routeIs('roles-and-permissions.index') ? 'active': ''}}" href="{{route('roles-and-permissions.index')}}">
                            Funções e permissões
                          </a>
                          @endcan
                          @can('location-list')
                          <a class="dropdown-item {{request()->routeIs('locations.index') ? 'active': ''}}" href="{{route('locations.index')}}">
                           Unidades operacionais
                          </a>
                          @endcan
                          @can('file-list')
                          <a class="dropdown-item {{request()->routeIs('files.index') ? 'active': ''}}" href="{{route('files.index')}}">
                            Modelos Word
                          </a>
                          @endcan
                        </div>
                     </li>
                     @endcanany
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
                        <span class="avatar avatar-sm" style="background-image: url('{{avatar()}}')"></span>
                        <div class="d-none d-xl-block ps-2">
                           <div>{{$user->name}}</div>
                           <div class="mt-1 small text-secondary">{{ $user->roles->pluck('name')->implode(', ') }}</div>
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
      <div class="modal modal-blur fade" id="modal-profile" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
               <form action="{{route('users.update-profile')}}" method="POST" class="needs-validation" novalidate>
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                     <h5 class="modal-title">Editar usuário</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" value="{{$user->name}}" required>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Endereço de e-mail</label>
                        <input type="email" class="form-control" name="email" value="{{$user->email}}" required>
                     </div>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                       <div class="col-lg-6">
                         <div class="mb-3">
                           <label class="form-label">{{ __('Password') }}</label>
                           <input type="password" class="form-control" name="password" placeholder="password">
                         </div>
                       </div>
                       <div class="col-lg-6">
                         <div class="mb-3">
                           <label class="form-label">{{ __('Confirm Password') }}</label>
                           <input type="password" class="form-control" name="password_confirmation" placeholder="password">
                         </div>
                       </div>
                     </div>
                   </div>
                  <div class="modal-footer">
                     <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                     Cancelar
                     </a>
                     <button type="submit" class="btn btn-primary ms-auto">
                     Editar usuário
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <script src="{{ mix('js/app.js') }}" defer></script>
      @stack('modals')
      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      @stack('scripts')
      <script src="{{asset('js/functions.js')}}"></script>
   </body>
</html>
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
               <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>Usuarios
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
               @can('user-create')
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#user-create">
               Novo usuário
               </a>
               @endcan
            </div>
         </div>
      </div>
   </div>
</div>
<div class="page-body">
   <div class="container-xl">
      @include('layouts.flash-message')
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Usuários do sistema</h3>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-75">Usuário</th>
                     <th class="w-25">Funções</th>
                     <th>Ação</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($users as $user)
                  <tr>
                     <td>
                        <div class="d-flex py-1 align-items-center">
                          <span class="avatar me-2" style="background-image: url({{avatar($user)}})"></span>
                          <div class="flex-fill">
                            <div class="font-weight-medium">{{$user->name}}</div>
                            <div class="text-secondary"><a href="mailto:{{$user->email}}" class="text-reset">{{$user->email}}</a></div>
                          </div>
                        </div>
                     </td>
                     <td>
                        @foreach ($user->roles as $role)
                           <span class="status status-blue">
                              <span class="status-dot"></span>
                              {{$role->name}}
                           </span>
                        @endforeach
                     </td>
                     <td>
                        @can('user-edit')
                        <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$user->id}}">
                        Editar
                        </a>
                        @endcan
                        @can('user-delete')
                        <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete" data-bs-id="{{$user->id}}">
                        Excluir
                        </a>
                        @endcan
                     </td>
                  </tr>
                  @empty
                  @endforelse
               </tbody>
            </table>
         </div>
         <div class="card-footer">
            {{ $users->withQueryString()->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
@push('modals')
@can('user-create')
{{--! modal user-create--}}
<div class="modal modal-blur fade" id="user-create" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form action="{{route('users.store')}}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Novo usuário</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" placeholder="Administrator" required>
               </div>
               <div class="mb-3">
                  <label class="form-label">Endereço de e-mail</label>
                  <input type="email" class="form-control" name="email" placeholder="admin@admin.com" required>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                 <div class="col-lg-6">
                   <div class="mb-3">
                     <label class="form-label">{{ __('Password') }}</label>
                     <input type="password" class="form-control" name="password" placeholder="password" required>
                   </div>
                 </div>
                 <div class="col-lg-6">
                   <div class="mb-3">
                     <label class="form-label">{{ __('Confirm Password') }}</label>
                     <input type="password" class="form-control" name="password_confirmation" placeholder="password" required>
                   </div>
                 </div>
               </div>
             </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <label class="form-label">Funções</label>
                  </div>
                  @foreach ($roles as $role)
                  <div class="col-lg-3 mb-3">
                     <label class="form-check">
                        <input type="checkbox" name="roles[]" class="form-check-input" value="{{$role->name}}">
                        <span class="form-check-label">{{$role->name}}</span>
                     </label>
                  </div>
                  @endforeach
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Novo usuário
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('user-edit')
{{--! modal user-edit--}}
<div class="modal modal-blur fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar usuário</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" placeholder="Administrator" required>
               </div>
               <div class="mb-3">
                  <label class="form-label">Endereço de e-mail</label>
                  <input type="email" class="form-control" name="email" placeholder="admin@admin.com" required>
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
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <label class="form-label">Funções</label>
                  </div>
                  @foreach ($roles as $role)
                  <div class="col-lg-3 mb-3">
                     <label class="form-check">
                        <input type="checkbox" name="roles[]" class="form-check-input" value="{{$role->name}}">
                        <span class="form-check-label">{{$role->name}}</span>
                     </label>
                  </div>
                  @endforeach
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
@endcan
@can('user-delete')
<div class="modal modal-blur fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-sm modal-dialog-centered" user="document">
      <div class="modal-content">
         <form method="POST">
            @method('DELETE')
            @csrf
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
               <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
               <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M12 9v4" />
                  <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                  <path d="M12 16h.01" />
               </svg>
               <h3>Tem certeza?</h3>
               <div class="text-secondary">Você realmente deseja excluir esse usuário? O que você fizer não poderá ser desfeito.</div>
            </div>
            <div class="modal-footer">
               <div class="w-100">
                  <div class="row">
                     <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                        Cancelar
                        </a>
                     </div>
                     <div class="col">
                        <button type="submit" class="btn btn-danger w-100">
                           Excluir
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@endpush
@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle"> </div>
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
      
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Usuários do sistema</h3>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-50">Usuário</th>
                     <th class="w-25">Cargo<br>Departamento > Setor</th>
                     <th class="w-25">Funções</th>
                     @canany(['user-edit', 'user-delete'])<th></th>@endcanany
                  </tr>
               </thead>
               <tbody>
                  @forelse ($users as $user)
                  <tr>
                     <td>
                        <div class="d-flex py-1 align-items-center">
                          <span class="avatar me-2" style="background-image: url({{avatar($user->name)}})"></span>
                          <div class="flex-fill">
                            <div class="font-weight-medium">{{$user->name}}</div>
                            <div class="text-secondary"><a href="mailto:{{$user->email}}" class="text-reset">{{$user->email}}</a></div>
                          </div>
                        </div>
                     </td>
                     <td>
                        {{$user->position ?? 'Cargo não informado'}}
                        <br>
                        <div class="datagrid-content">
                           <div class="avatar-list avatar-list-stacked">
                                 @if ($user->sectors->count() > 0)
                                    <small class="fw-bold">{{$user->current_department}}</small>
                                    <span class="avatar avatar-xs rounded" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Acesso a outros setores: {{ $user->sectors->pluck('name')->reject(fn($name) => $name === $user->current_department)->implode(', ') ?: 'Setor não informado' }}">+{{$user->sectors->count()}}</span>
                                 @else
                                 <small class="fw-bold">{{$user->current_department}}</small>
                                 @endif
                              </div>
                           </div>
                     </td>
                     <td>
                        {{ $user->roles->pluck('name')->implode(', ') ?: 'Função não informada' }}
                     </td>
                     @canany(['user-edit', 'user-delete'])
                     <td>
                        <div class="btn-list flex-nowrap">
                           <div class="dropdown position-static">
                              <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                              Ações
                              </button>
                              <div class="dropdown-menu dropdown-menu-end">
                                 @can('user-edit')
                                 <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$user->id}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                       <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                       <path d="M16 5l3 3"></path>
                                    </svg>
                                    Editar
                                 </a>
                                 @endcan
                                 @can('user-delete')
                                 <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#delete" data-bs-id="{{$user->id}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M4 7l16 0"></path>
                                       <path d="M10 11l0 6"></path>
                                       <path d="M14 11l0 6"></path>
                                       <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                       <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                    Excluir
                                 </a>
                                 @endcan
                              </div>
                           </div>
                        </div>
                     </td>
                     @endcanany
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
               <div class="row">
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Administrator" required>
                     </div>
                  </div>
                  {{--<div class="col-lg-5">
                     <div class="mb-3">
                        <label class="form-label">Cargo</label>
                        <input type="text" class="form-control" name="position" placeholder="Chefe de setor" required>
                     </div>
                  </div>--}}
                  <div class="col-lg-8">
                     <div class="mb-3">
                        <label class="form-label">Endereço de e-mail</label>
                        <input type="email" class="form-control" name="email" placeholder="admin@admin.com" required>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Usuário ERP</label>
                        <input type="text" class="form-control" name="username">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                 <div class="col-lg-6">
                   <div class="mb-3">
                     <label class="form-label">{{ __('Senha') }}</label>
                     <input type="password" class="form-control" name="password" placeholder="password" required>
                   </div>
                 </div>
                 <div class="col-lg-6">
                   <div class="mb-3">
                     <label class="form-label">{{ __('Confirmar senha') }}</label>
                     <input type="password" class="form-control" name="password_confirmation" placeholder="password" required>
                   </div>
                 </div>
               </div>
             </div>
             <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Cargo e departamento alocado</label>
                        <div class="input-group">
                           <input type="text" class="form-control" name="position" placeholder="Chefe de setor" required>
                           <select name="current_department" class="form-select" required>
                              <option value="" disabled selected>Selecione</option>
                              @foreach ($departments as $department)
                              <option value="{{$department->name}}">{{$department->name}}</option>
                              @endforeach
                           </select>
                        </div>
                        <small class="form-hint">Essas informações serão registradas no rodapé do relatório circunstanciado.</small>
                     </div>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <label class="form-label">Acesso a outros setores</label>
                     <select name="sectors[]" class="form-select" multiple required>
                        @foreach ($departments as $department)
                           <optgroup label="{{$department->id}} - {{$department->name}}">
                              @foreach ($department->sectors as $sector)
                                 <option value="{{$sector->id}}">{{$sector->name}}</option>
                              @endforeach
                           </optgroup>
                        @endforeach
                     </select>
                     <small class="form-hint">O colaborador poderá cadastrar itens nos respectivos setores</small>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <label class="form-label">Funções</label>
                  </div>
                  @foreach ($roles as $role)
                  <div class="col-lg-3">
                     <label class="form-check">
                        <input type="checkbox" name="roles[]" class="form-check-input" value="{{$role->id}}">
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
               <div class="row">
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Administrator" required>
                     </div>
                  </div>
                  <div class="col-lg-8">
                     <div class="mb-3">
                        <label class="form-label">Endereço de e-mail</label>
                        <input type="email" class="form-control" name="email" placeholder="admin@admin.com" required>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Usuário ERP</label>
                        <input type="text" class="form-control" name="username">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                 <div class="col-lg-6">
                   <div class="mb-3">
                     <label class="form-label">{{ __('Senha') }}</label>
                     <input type="password" class="form-control" name="password" placeholder="password">
                   </div>
                 </div>
                 <div class="col-lg-6">
                   <div class="mb-3">
                     <label class="form-label">{{ __('Confirmar senha') }}</label>
                     <input type="password" class="form-control" name="password_confirmation" placeholder="password">
                   </div>
                 </div>
               </div>
             </div>
             <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Cargo e departamento alocado</label>
                        <div class="input-group">
                           <input type="text" class="form-control" name="position" placeholder="Chefe de setor" required>
                           <select name="current_department" class="form-select" required>
                              <option value="" disabled selected>Selecione</option>
                              @foreach ($departments as $department)
                              <option value="{{$department->name}}">{{$department->name}}</option>
                              @endforeach
                           </select>
                        </div>
                        <small class="form-hint">Essas informações serão registradas no rodapé do relatório circunstanciado.</small>
                     </div>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <label class="form-label">Acesso a outros setores</label>
                     <select name="sectors[]" class="form-select" multiple required>
                        @foreach ($departments as $department)
                           <optgroup label="{{$department->id}} - {{$department->name}}">
                              @foreach ($department->sectors as $sector)
                                 <option value="{{$sector->id}}">{{$sector->name}}</option>
                              @endforeach
                           </optgroup>
                        @endforeach
                     </select>
                     <small class="form-hint">O colaborador poderá cadastrar itens nos respectivos setores</small>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <label class="form-label">Funções</label>
                  </div>
                  @foreach ($roles as $role)
                  <div class="col-lg-3">
                     <label class="form-check">
                        <input type="checkbox" name="roles[]" class="form-check-input" value="{{$role->id}}">
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
   <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST">
            @method('DELETE')
            @csrf
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
               
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
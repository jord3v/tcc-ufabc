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
               <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg> Funções e permissões
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
               @can('role-create')
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#role-create">
               Nova função
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
            <h3 class="card-title">Funções</h3>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-75">Nome da função</th>
                     <th class="w-10">Permissões</th>
                     @canany(['role-edit', 'role-delete'])<th></th>@endcanany
                  </tr>
               </thead>
               <tbody>
                  @forelse ($roles as $role)
                  <tr>
                     <td>{{$role->name}}</td>
                     <td>{{$role->permissions->count()}}</td>
                     @canany(['role-edit', 'role-delete'])
                     <td>
                        <div class="btn-list flex-nowrap">
                           <div class="dropdown position-static">
                              <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                              Ações
                              </button>
                              <div class="dropdown-menu dropdown-menu-end">
                                 @can('role-edit')
                                 <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$role->id}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                       <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                       <path d="M16 5l3 3"></path>
                                    </svg>
                                    Editar
                                 </a>
                                 @endcan
                                 @can('role-delete')
                                 <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#delete" data-bs-id="{{$role->id}}">
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
            {{ $roles->withQueryString()->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
@push('modals')
@can('role-create')
{{--! modal role-create--}}
<div class="modal modal-blur fade" id="role-create" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form action="{{route('roles-and-permissions.store')}}" method="POST"  class="needs-validation" novalidate>
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Nova função</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" placeholder="Administrator" required>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <label class="form-label">Permissões</label>
                  </div>
                  @foreach ($permissions as $key => $permission)
                  <div class="col-lg-3 mb-3">
                     <label class="form-label">{{$key}}</label>
                     @foreach ($permission as $item)
                     <label class="form-check">
                     <input type="checkbox" name="permissions[]" class="form-check-input" value="{{$item->name}}">
                     <span class="form-check-label">{{$item->name}}</span>
                     </label>
                     @endforeach
                  </div>
                  @endforeach
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Nova função
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('role-edit')
{{--! modal role-edit--}}
<div class="modal modal-blur fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar função</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" placeholder="Administrator" required>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <label class="form-label">Permissões</label>
                  </div>
                  @foreach ($permissions as $key => $permission)
                  <div class="col-lg-3 mb-3">
                     <label class="form-label">{{$key}}</label>
                     @foreach ($permission as $item)
                     <label class="form-check">
                     <input type="checkbox" name="permissions[]" class="form-check-input" value="{{$item->name}}">
                     <span class="form-check-label">{{$item->name}}</span>
                     </label>
                     @endforeach
                  </div>
                  @endforeach
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Editar função
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('role-delete')
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
               <div class="text-secondary">Você realmente deseja excluir essa função? O que você fizer não poderá ser desfeito.</div>
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
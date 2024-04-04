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
               Funções e permissões
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
                     <th>Ação</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($roles as $role)
                  <tr>
                     <td>{{$role->name}}</td>
                     <td>{{$role->permissions->count()}}</td>
                     <td>
                        @can('role-edit')
                        <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#role-edit" data-bs-id="{{$role->id}}">
                        Editar
                        </a>
                        @endcan
                        @can('role-delete')
                        <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#role-delete" data-bs-id="{{$role->id}}">
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
         <form action="{{route('roles-and-permissions.store')}}" method="POST">
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Nova função</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" placeholder="Administrator">
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
                     <input type="checkbox" name="permissions[]" class="form-check-input" value="{{$item->id}}">
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
<div class="modal modal-blur fade" id="role-edit" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar função</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" placeholder="Administrator">
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
                     <input type="checkbox" name="permissions[]" class="form-check-input" value="{{$item->id}}">
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
<div class="modal modal-blur fade" id="role-delete" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
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
@push('scripts')
<script>
   const roleEdit = document.getElementById('role-edit')
   const roleEditForm = roleEdit.querySelector('form');
   
   if (roleEdit) {
      roleEdit.addEventListener('show.bs.modal', event => {
       const button = event.relatedTarget
       const id = button.getAttribute('data-bs-id')
       var route = "{{route('roles-and-permissions.index')}}/" +id
       fetch(route)
       .then(response => {
           if (!response.ok) {
               throw new Error('Erro ao carregar dados do servidor');
           }
           return response.json();
       })
       .then(data => {
         roleEditForm.action = route;
            roleEdit.querySelector('input[name="name"]').value = data.name;
            data.permissions.forEach(permission => {
                const checkbox = document.querySelector(`#role-edit input[value="${permission.id}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
       })
       .catch(error => {
           console.error('Erro:', error);
       });
     })
   }
</script>
<script>
   const roleDelete = document.getElementById('role-delete')
   const roleDeleteForm = roleDelete.querySelector('form');
   if (roleDelete) {
      roleDelete.addEventListener('show.bs.modal', event => {
         const button = event.relatedTarget
         const id = button.getAttribute('data-bs-id')
         var route = "{{route('roles-and-permissions.index')}}/" +id
         roleDeleteForm.action = route;
      });
   }
</script>
@endpush
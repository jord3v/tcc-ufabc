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
               <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>Unidades operacionais
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
               @can('location-create')
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#location-create">
               Nova localidade
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
            <h3 class="card-title">Unidades operacionais da instituição</h3>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-75">localidade</th>
                     <th class="w-25">Responsável</th>
                     <th class="w-25">Situação</th>
                     @can('location-edit')
                     <th></th>
                     @endcan
                  </tr>
               </thead>
               <tbody>
                  @forelse ($locations as $location)
                  <tr>
                     <td>
                        {{$location->name}}
                     </td>
                     <td>{{$location->user->name}}</td>
                     <td>
                        @if ($location->active)
                           <span class="status status-primary">
                              <span class="status-dot"></span>
                              Ativo
                           </span>
                        @else
                           <span class="status status-default">
                              <span class="status-dot"></span>
                              Inativo
                           </span>
                        @endif
                     </td>
                     @can('location-edit')
                     <td>
                        <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$location->id}}">
                        Editar
                        </a>
                     </td>
                     @endcan
                  </tr>
                  @empty
                  @endforelse
               </tbody>
            </table>
         </div>
         <div class="card-footer">
            {{ $locations->withQueryString()->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
@push('modals')
@can('location-create')
{{--! modal location-create--}}
<div class="modal modal-blur fade" id="location-create" tabindex="-1" user="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form action="{{route('locations.store')}}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Nova localidade</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" placeholder="SEDE" required>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Nova localidade
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('location-edit')
{{--! modal location-edit--}}
<div class="modal modal-blur fade" id="edit" tabindex="-1" user="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar localidade</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="name" disabled required>
               </div>
               <div class="mb-3">
                  <div class="form-label">Ativo</div>
                  <label class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="active" checked>
                  <span class="form-check-label"></span>
                  </label>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Editar localidade
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('location-delete')
<div class="modal modal-blur fade" id="location-delete" tabindex="-1" user="dialog" aria-hidden="true">
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
               <div class="text-secondary">Você realmente deseja excluir esse localidade? O que você fizer não poderá ser desfeito.</div>
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
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
               <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-docx"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M2 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" /><path d="M17 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" /><path d="M9.5 15a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1 -3 0v-3a1.5 1.5 0 0 1 1.5 -1.5z" /><path d="M19.5 15l3 6" /><path d="M19.5 21l3 -6" /></svg>
               Modelos Word
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
               @can('file-create')
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#file-create">
               Novo template
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
            <h3 class="card-title">Lista de templates</h3>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-75">Nome do arquivo</th>
                     <th class="w-25">Responsável</th>
                     <th class="w-25">Situação</th>
                     <th>Ação</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($files as $file)
                  <tr>
                     <td>
                        {{$file->filename}}
                     </td>
                     <td>
                        {{$file->user->name}}
                     </td>
                     <td>
                        @if ($file->active)
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
                     <td>
                        @can('file-edit')
                        <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$file->id}}">
                        Editar
                        </a>
                        @endcan
                        @can('file-delete')
                        <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#file-delete" data-bs-id="{{$file->id}}">
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
            {{ $files->withQueryString()->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
@push('modals')
@can('file-create')
{{--! modal file-create--}}
<div class="modal modal-blur fade" id="file-create" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form action="{{route('files.store')}}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Novo template</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <div class="form-label">Arquivo</div>
                  <input type="file" class="form-control" name="file" accept=".doc, .docx" required/>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Novo template
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('file-edit')
{{--! modal file-edit--}}
<div class="modal modal-blur fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar template</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="filename" disabled>
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
               Editar template
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@endpush
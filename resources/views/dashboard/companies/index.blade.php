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
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-skyscraper"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 21l18 0"></path><path d="M5 21v-14l8 -4v18"></path><path d="M19 21v-10l-6 -4"></path><path d="M9 9l0 .01"></path><path d="M9 12l0 .01"></path><path d="M9 15l0 .01"></path><path d="M9 18l0 .01"></path></svg>Prestadores de serviços
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
               @can('company-create')
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#company-create">
               Novo prestador de serviço
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
            <h3 class="card-title">Lista de prestadores de serviços</h3>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-75">Razão social/cnpj</th>
                     <th class="w-25">Nome comercial</th>
                     <th class="w-25">Responsável</th>
                     @can('company-edit')
                     <th></th>
                     @endcan
                  </tr>
               </thead>
               <tbody>
                  @forelse ($companies as $company)
                  <tr>
                     <td>
                        <div class="d-flex py-1 align-items-center">
                           <div class="flex-fill">
                             <div class="font-weight-medium">{{$company->name}}</div>
                             <div class="text-secondary">{{$company->cnpj}}</div>
                           </div>
                        </div>
                     </td>
                     <td>{{$company->commercial_name}}</td>
                     <td>{{$company->user->name}}</td>
                     @can('company-edit')
                     <td>
                        <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$company->id}}">
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
            {{ $companies->withQueryString()->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
@push('modals')
@can('company-create')
{{--! modal company-create--}}
<div class="modal modal-blur fade" id="company-create" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form action="{{route('companies.store')}}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Novo prestador de serviço</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Razão da empresa</label>
                  <input type="text" class="form-control"  name="name" required>
                  <small class="form-hint">Nome que vai ser lançado no Relatório Circustânciado</small>
               </div>
               <div class="mb-3">
                  <label class="form-label">Nome comercial</label>
                  <input type="text" name="commercial_name" class="form-control" required>
                  <small class="form-hint">Nome que vai ser lançado no protocolo SPW</small>
               </div>
               <div class="mb-3">
                  <label class="form-label">CNPJ</label>
                  <input type="text" name="cnpj" class="cnpj form-control" required>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Novo prestador de serviço
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('company-edit')
{{--! modal company-edit--}}
<div class="modal modal-blur fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar prestador de serviço</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Razão da empresa</label>
                  <input type="text" class="form-control"  name="name" required>
                  <small class="form-hint">Nome que vai ser lançado no Relatório Circustânciado</small>
               </div>
               <div class="mb-3">
                  <label class="form-label">Nome comercial</label>
                  <input type="text" name="commercial_name" class="form-control" required>
                  <small class="form-hint">Nome que vai ser lançado no protocolo SPW</small>
               </div>
               <div class="mb-3">
                  <label class="form-label">CNPJ</label>
                  <input type="text" name="cnpj" class="cnpj form-control" required>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Editar prestador de serviço
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('company-delete')
<div class="modal modal-blur fade" id="company-delete" tabindex="-1" role="dialog" aria-hidden="true">
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
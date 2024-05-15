@php 
$_GET['year'] = isset($_GET['year']) ? $_GET['year'] : now()->format('Y');
@endphp
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
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-description"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><path d="M9 17h6"></path><path d="M9 13h6"></path></svg> Relatórios
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
               @can('report-create')
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#report-create">
               Novo relatório circunstanciados
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
      <form action="{{route('payments.fill')}}" method="POST" class="needs-validation" novalidate>
         @csrf
         <div class="card">
            <div class="card-header">
               <div>
               <h3 class="card-title">
                  Lista de Relatórios circunstanciados
               </h3>
               </div>
               <div class="card-actions">
                  <div class="form-floating">
                     <select class="form-select" id="year-note">
                        @for ($year = 2025; $year > 2020; $year--)
                           <option value="{{ $year }}" {{$year == $_GET['year'] ? 'selected' : ''}}>notas de {{ $year }}</option>
                        @endfor
                     </select>
                     <label for="year-note">Selecione o ano</label>
                  </div>
               </div>
            </div>
            <div class="table-responsive">
               <table class="table table-hover card-table table-vcenter text-nowrap datatable">
                  <thead>
                     <tr>
                        <th>Prestador de serviço<br>Unidade operacional</th>
                        <th>Empenho</th>
                        <th>Gestor do relatório</th>
                        <th>Departamento</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse ($reports as $company => $report)
                        <tr>
                           <td colspan="4">
                              <strong>{{$company}}</strong>
                           </td>
                           <td>
                              <input type="checkbox" class="form-check-input group-checkbox">
                           </td>
                        </tr>
                        @foreach ($report as $item)
                        <tr>
                           <td><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path></svg>{{$item->location->name}}</td>
                           <td>{{$item->note->number}}/{{$item->note->year}}<br>{{$item->note->modality}}</td>
                           <td>{{$item->manager}}</td>
                           <td>{{$item->department}}</td>
                           <td><input type="checkbox" class="form-check-input" name="reports[]" value="{{$item->id}}"></td>
                        </tr>
                        @endforeach
                     @empty
                        <tr>
                           <td colspan="5" class="text-center bg-dark-lt"> nada encontrado</td>
                        </tr>
                     @endforelse
                     {{--@foreach ($companies as $company)
                        <tr>
                           <td colspan="4">
                              <strong>{{$company->name}} - {{$company->commercial_name}}<br><span class="text-muted">{{$company->cnpj}}</span></strong>
                           </td>
                           <td>
                              <input type="checkbox" class="form-check-input group-checkbox">
                           </td>
                        </tr>    
                        @forelse ($company->reports as $report)
                           <tr>
                              <td><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path></svg>{{$report->location->name}}</td>
                              <td>{{$report->note->number}}/{{$report->note->year}}<br>{{$report->note->modality}}</td>
                              <td>{{$report->manager}}</td>
                              <td>{{$report->department}}</td>
                              <td><input type="checkbox" class="form-check-input" name="reports[]" value="{{$report->id}}"></td>
                           </tr>
                        @empty
                           <tr>
                              <td colspan="4">Nenhum relatório criado</td>
                           </tr>
                        @endforelse
                     @endforeach--}}
                  </tbody>
               </table>
            </div>
            <div class="card-footer">
               <div class="d-flex">
                  <button type="submit" class="btn btn-success ms-auto">Adicionar pagamentos</button>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
@endsection
@push('modals')
@can('report-create')
{{--! modal report-create--}}
<div class="modal modal-blur fade" id="report-create" tabindex="-1" user="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form action="{{route('reports.store')}}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Novo relatório circunstanciados</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-8">
                     <div class="mb-3">
                        <label class="form-label">Prestador de serviço</label>
                        <select class="form-select" name="company_id" required>
                           <option value="" selected>Selecione o prestador de serviço</option>
                           @forelse ($companies as $company)
                           <option value="{{$company->id}}">{{$company->name}}</option>
                           @empty
                           @endforelse
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Unidade operacional</label>
                        <select class="form-select" name="location_id" required>
                           <option value="" selected>Selecione a unidade</option>
                           @forelse ($locations as $location)
                           <option value="{{$location->id}}">{{$location->name}}</option>
                           @empty
                           @endforelse
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Nota de empenho</label>
                        <select class="form-select" name="note_id" required>
                           <option value="" selected>Selecione a nota</option>
                           @forelse ($notes as $note)
                           <option value="{{$note->id}}">{{$note->number}}/{{$note->year}} - {{$note->process}} - {{$note->modality}} - {{$note->service}}</option>
                           @empty
                           @endforelse
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Gestor do relatório</label>
                        <input type="text" class="form-control" name="manager" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Departamento</label>
                        <input type="text" class="form-control" name="department" required>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Modelo word</label>
                        <select class="form-select" name="file_id" required>
                           <option value="" selected>Selecione o template word</option>
                           @forelse ($files as $file)
                           <option value="{{$file->id}}">{{$file->filename}}</option>
                           @empty
                           @endforelse
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Novo relatório circunstanciados
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('report-delete')
<div class="modal modal-blur fade" id="report-delete" tabindex="-1" user="dialog" aria-hidden="true">
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
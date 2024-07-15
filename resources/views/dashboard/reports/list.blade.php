@php 
$_GET['company'] = isset($_GET['company']) ? $_GET['company'] : '';
$_GET['reference'] = isset($_GET['reference']) ? $_GET['reference'] : '';
$_GET['start'] = $_GET['start'] ?? now()->startOfWeek()->format('Y-m-d');
$_GET['end'] = $_GET['end'] ?? now()->endOfWeek()->format('Y-m-d');
@endphp
@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle"> </div>
            <h2 class="page-title">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-description"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><path d="M9 17h6"></path><path d="M9 13h6"></path></svg> Rel. circunstanciados - Fazer download
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="page-body">
   <div class="container-xl">
      <form action="">
         <div class="card card-borderless mb-3">
            <div class="card-header">
               <h3 class="card-title">Busca rápida</h3>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col-5">
                  <label class="form-label">Prestador de serviço</label>
                  <select name="company" class="form-select">
                        <option value="">Todos</option>
                     @foreach ($companies as $company)
                        <option value="{{$company->id}}" {{$_GET['company'] == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                     @endforeach
                  </select>
                  </div>
                  <div class="col-3">
                  <label class="form-label">Período de execução</label>
                  <input type="month" name="reference" class="form-control" value="{{$_GET['reference']}}">
                  </div>
                  <div class="col-4">
                  <label class="form-label">Intervalo de pagamentos</label>
                  <div class="row g-2">
                     <div class="col-6">
                     <input type="date" name="start" class="form-control" value="{{$_GET['start']}}">
                     </div>
                     <div class="col-6">
                        <input type="date" name="end" class="form-control" value="{{$_GET['end']}}">
                     </div>
                  </div>
                  </div>
               </div>           
            </div>
            <div class="card-footer">
               <button class="btn">Filtrar</button>
            </div>
         </div>
      </form>
      <form action="{{route('reports.download')}}" method="POST" class="needs-validation" novalidate>
         @csrf
         <div class="card">
            <div class="card-header">
               <h3 class="card-title">Histórico de pagamentos</h3>
               <div class="card-actions">
                  <button type="submit" class="dynamic-button btn btn-outline-success" data-base-text="Download dos itens selecionados" disabled>Download dos itens selecionados</button>   
               </div>
            </div>
            <div class="table-responsive">
               {{--<table class="table card-table table-vcenter text-nowrap datatable" id="tabela">
                  <thead>
                     <tr>
                        <th>Pagamento</th>
                        <th>Protocolo</th>
                        <th><button type="submit" class="btn btn-sm btn-success ms-auto">download</button></th>
                        <th class="w-5">Digitalização</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse ($payments as $payment)
                     <tr data-company="{{$payment->report->company->id}}" data-reference="{{$payment->reference->format('Y-m-d')}}" data-location="{{$payment->report->location->id}}">
                        <td>
                           {{$payment->report->company->name}}<br>
                           {{getPrice($payment->price)}} - {{$payment->report->note->service}} - {{$payment->report->location->name}} - {{str()->upper(reference($payment->reference))}}
                        </td>
                        <td>
                           @if ($payment->process)
                              ADM-{{$payment->process}}
                           @else
                           <form action="{{route('protocols.store')}}" method="post">
                              @csrf
                                 <input type="hidden" name="id" value="{{$payment->id}}">
                                 <input type="hidden" name="uuid" value="{{$payment->uuid}}">
                                 <button type="submit" class="btn btn-outline-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01"></path></svg> Gerar protocolo
                                 </button>
                              </form>
                           @endif
                        </td>
                        <td>
                           <input type="checkbox" name="" class="form-check-input">
                        </td>
                        <td>
                           @if ($payment->process)
                           <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#add-attachment" data-bs-id="{{$payment->id}}" data-bs-uuid="{{$payment->uuid}}" data-bs-process="ADM-{{$payment->process}}">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-paperclip"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" /></svg>
                              Incluir anexo
                           </a>
                           @endif
                        </td>
                     </tr>
                     @empty
                        
                     @endforelse
                  </tbody>
               </table>--}}
               <table class="table card-table table-vcenter text-nowrap datatable" id="tabela">
                  <thead>
                     <tr>
                        <th></th>
                        <th>Pagamento</th>
                        <th>Protocolo</th>
                        @can('payment-create')
                        {{--<th><button type="submit" class="btn btn-sm btn-success ms-auto">download</button></th>--}}
                        @endcan
                        <th class="w-5">Digitalização</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse ($payments as $date => $payment)
                        <tr class="bg-white">
                           @can('payment-create')
                              <td><input type="checkbox" class="form-check-input group-checkbox-reports-downloads"></td>
                           @endcan
                           <td colspan="{{auth()->user()->can('payment-create') ? '3' : '4'}}">
                              <u class="fw-bold text-warning">Relatórios circunstanciados gerados em <strong>{{date('d/m/Y', strtotime($date))}}</strong></u>
                           </td>
                        </tr>
                        @foreach ($payment as $item)
                        <tr data-company="{{$item->report->company->id}}" data-reference="{{$item->reference->format('Y-m-d')}}" data-location="{{$item->report->location->id}}" data-signature="{{$date}}">
                           <td><input type="checkbox" name="payments[]" class="form-check-input" value="{{$item->uuid}}"></td>
                           <td>
                              {{$item->report->company->name}}<br>
                              {{getPrice($item->price)}} - {{(strlen($item->report->note->service) > 50 ? substr($item->report->note->service, 0, 50) . "..." : $item->report->note->service)}} - {{$item->report->location->name}} - {{str()->upper(reference($item->reference))}}
                           </td>
                           <td>
                              @if ($item->process)
                                 ADM-{{$item->process}}
                              @else
                                 <a href="{{route('protocols.show', $item->uuid)}}" class="btn btn-outline-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01"></path></svg>Gerar protocolo
                                 </a>
                              @endif
                           </td>
                           <td>
                              @if ($item->process)
                              <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#add-attachment" data-bs-id="{{$item->id}}" data-bs-uuid="{{$item->uuid}}" data-bs-process="ADM-{{$item->process}}">
                                 <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-paperclip"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" /></svg>
                                 Incluir anexo
                              </a>
                              @endif
                           </td>
                        </tr>
                        @endforeach
                     @empty
                        
                     @endforelse
                     {{--@forelse ($reports as $company => $report)
                        <tr>
                           <td colspan="{{auth()->user()->can('payment-create') ? '4' : '5'}}">
                              <strong>{{$company}}</strong>
                           </td>
                           @can('payment-create')
                           <td>
                              <input type="checkbox" class="form-check-input group-checkbox">
                           </td>
                           @endcan
                        </tr>
                        @foreach ($report->sortBy('location.name') as $item)
                        <tr>
                           <td><a href="{{route('payments.index', ['company' => $item->company_id, 'report' => $item->id])}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path></svg>{{$item->location->name}}</a></td>
                           <td>{{$item->note->number}}/{{$item->note->year}}<br>{{$item->note->modality}}</td>
                           <td>{{$item->manager}}</td>
                           <td>{{$item->department}}</td>
                           @can('payment-create')
                           <td><input type="checkbox" class="form-check-input" name="reports[]" value="{{$item->id}}"></td>
                           @endcan
                        </tr>
                        @endforeach
                     @empty
                        <tr>
                           <td colspan="5" class="text-center bg-dark-lt"> nada encontrado</td>
                        </tr>
                     @endforelse--}}
                  </tbody>
               </table>
            </div>
            <div class="card-footer">
               <div class="row">
                  <div class="col-auto d-flex align-items-center ps-2">
                  <span class="legend me-2 highlight"></span>
                  <span class="d-none d-md-inline d-lg-none d-xxl-inline ms-2 text-secondary">Pagamentos que vão no mesmo protocolo</span>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
@endsection
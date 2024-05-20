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
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01"></path></svg> Protocolos
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
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Histórico de pagamentos</h3>
            <div class="card-actions">
               <div class="row">
                  <div class="col-auto d-flex align-items-center ps-2">
                    <span class="legend me-2 highlight"></span>
                    <span class="d-none d-md-inline d-lg-none d-xxl-inline ms-2 text-secondary">Pagamentos que vão no mesmo protocolo</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable" id="tabela">
               <thead>
                  <tr>
                     <th>Pagamento</th>
                     <th>Protocolo</th>
                     <th class="w-5">Digitalização</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($payments as $payment)
                  <tr data-company="{{$payment->report->company->id}}" data-reference="{{$payment->reference->format('Y-m-d')}}" data-location="{{$payment->report->location->id}}">
                      <td>
                          {{$payment->report->company->name}}<br>
                          {{$payment->report->note->service}} - {{$payment->report->location->name}} - {{Str::upper(reference($payment->reference))}}
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
            </table>
         </div>
         <div class="card-footer">
            {{ $payments->withQueryString()->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
@php 
$_GET['company'] = isset($_GET['company']) ? $_GET['company'] : '';
$_GET['report'] = isset($_GET['report']) ? $_GET['report'] : '';
$_GET['active'] = isset($_GET['active']) ? $_GET['active'] : "1";
@endphp
@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle"> </div>
            <h2 class="page-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><path d="M8 13h1v3h-1z"></path><path d="M12 13v3"></path><path d="M15 13h1v3h-1z"></path></svg>
                {{$unresolved ? $unresolved->count().' protocolos não resolvidos' : 'Não há protocolos pendentes'}}
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('payments.index')}}" class="btn">
               Voltar
               </a>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="page-body">
   <div class="container-xl">
      <form action="{{route('payments.fill')}}" method="POST" class="needs-validation" novalidate>
       @csrf
      <div class="row row-deck row-cards">
         <div class="col-12">
            <div class="tags-list">
               @foreach ($_GET as $key => $item)
                  @if($item && $key !== 'active')
                  <span class="tag">
                     <strong id="{{$key}}">{{$key}}: {{replaceTag($item)}}</strong>
                     <a href="{{removeParams($key)}}" class="btn-close"></a>
                   </span>
                  @endif
               @endforeach
            </div>
         </div>
         <div class="col-md-3">
            <div class="w-100 sticky-top">
               <a href="{{route('payments.pending')}}" class="form-selectgroup-item w-100 btn text-muted fw-bold mb-2" id="pendings-button">
                  carregando
               </a>
               <a href="{{route('payments.pending')}}" class="form-selectgroup-item w-100 btn fw-bold active text-primary">
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 13h1v3h-1z" /><path d="M12 13v3" /><path d="M15 13h1v3h-1z" /></svg>{{$unresolved ? $unresolved->count().' protocolos não resolvidos' : 'Não há protocolos pendentes'}}
               </a>
            </div>
         </div>
         <div class="col-md-9">
            <div class="row row-cards">
               <div class="space-y">
                  <div class="card">
                     <div class="card-header">
                        <div>
                           <h3 class="card-title">
                              Protocolos
                           </h3>
                        </div>
                        <div class="card-actions">
                           <a href="{{route('protocols.update')}}" class="btn btn-outline-success" id="status-btn" onclick="checkStatus()">Verificar situação</a>
                       </div>
                     </div>
                     <div class="card-body p-0">
                        <div class="table-responsive">
                           <table class="table card-table table-vcenter text-nowrap datatable table-bordered">
                            <thead>
                                <tr>
                                   <th>Pagamento</th>
                                   <th>Protocolo</th>
                                </tr>
                             </thead>
                              <tbody>
                                @forelse ($unresolved as $payment)
                                <tr>
                                    <td>
                                       {{$payment->report->company->name}}<br>
                                       {{getPrice($payment->price)}} - {{(strlen($payment->report->note->service) > 50 ? substr($payment->report->note->service, 0, 50) . "..." : $payment->report->note->service)}} - {{$payment->report->location->name}} - {{str()->upper(reference($payment->reference))}}
                                    </td>
                                    <td>ADM-{{$payment->process}}<br> {{$payment->status}}</td>
                                 </tr>
                                @empty
                                <td class="td-truncate" colspan="2">
                                    <div class="alert alert-important bg-green-lt m-0" role="alert">
                                       <div class="d-flex fw-bold">
                                          <div>
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M5 12l5 5l10 -10"></path>
                                             </svg>
                                          </div>
                                          <div>Não há protocolos pendentes</div>
                                       </div>
                                    </div>
                                 </td>
                                @endforelse
                              </tbody>
                          </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </form>
   </div>
</div>
@endsection

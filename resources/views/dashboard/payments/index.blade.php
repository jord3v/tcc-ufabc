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
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path></svg>
               Pagamentos lançados
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
            <form method="get" autocomplete="off" novalidate="" class="w-100 sticky-top">
               <div class="form-label">Situação</div>
               <div class="mb-4">
               <div class="form-selectgroup w-100">
                  <label class="form-selectgroup-item">
                    <input type="radio" name="active" value="1" class="form-selectgroup-input" onchange='this.form.submit()' {{$_GET['active'] == '1' ? 'checked' : ''}}>
                    <span class="form-selectgroup-label"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><path d="M10 12l4 0" /></svg>
                      Ativo</span>
                  </label>
                  <label class="form-selectgroup-item">
                    <input type="radio" name="active" value="0" class="form-selectgroup-input" onchange='this.form.submit()' {{$_GET['active'] == '0' ? 'checked' : ''}}>
                    <span class="form-selectgroup-label"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 4h11a2 2 0 1 1 0 4h-7m-4 0h-3a2 2 0 0 1 -.826 -3.822" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 1.824 -1.18m.176 -3.82v-7" /><path d="M10 12h2" /><path d="M3 3l18 18" /></svg>
                      Arquivados</span>
                  </label>
               </div>
               </div>
               <div class="form-label">Empresa</div>
               <div class="mb-4">
                  <select class="form-select dynamic-select" name="company" onchange='this.form.submit()' data-span-id="company"> 
                     <option value="">Selecione</option>
                     @forelse ($companies as $company)
                     <option value="{{$company->id}}" {{$_GET['company'] == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                     @empty
                     <option value="">nenhuma empresa localizada</option>
                     @endforelse
                  </select>
               </div>
               <div class="form-label">Relatório circustanciado</div>
               <div class="mb-4">
                  <select class="form-control dynamic-select" name="report" onchange='this.form.submit()' data-span-id="report">
                     <option value="">Selecione</option>
                     @forelse ($filters as $key => $filter)
                     <optgroup label="{{$key}}">
                        @foreach ($filter as $item)
                        <option value="{{$item->id}}" {{$_GET['report'] == $item->id ? 'selected' : ''}}>{{$item->note->number}}/{{$item->note->year}}</option>    
                        @endforeach
                     </optgroup>
                     @empty
                     <option value="">nenhum relatório localizado</option>
                     @endforelse
                  </select>
               </div>
               @if ($_GET['company'] || $_GET['report'])
                  <div class="mt-5">
                     <a href="{{route('payments.index')}}" class="btn btn-outline-info w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 4h12v2.172a2 2 0 0 1 -.586 1.414l-3.914 3.914m-.5 3.5v4l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227" /><path d="M3 3l18 18" /></svg> Remover filtro
                     </a>
                  </div>
               @endif
            </form>
         </div>
         <div class="col-md-9">
            <div class="row row-cards">
               <div class="space-y">
                  <div class="card">
                     <div class="card-header">
                        <div>
                           <h3 class="card-title">
                              Pagamentos
                           </h3>
                           @if($report)
                           <p class="card-subtitle">
                              {{$report->note->service}} - <strong>{{$report->location->name}}</strong>
                           </p>
                           @php $amount = $report->note->amount - $report->payments->sum('price') @endphp
                           <p class="card-subtitle fw-bold">
                              Saldo a realizar: {{getPrice($amount)}} {{$item->note->end->diffForHumans()}}
                           </p>
                           @endif
                        </div>
                        @if($report)
                        @can('payment-create')
                           <div class="card-actions">
                              <form action="{{route('payments.fill')}}" method="post" class="needs-validation" novalidate autocomplete="off">
                                 @csrf
                                 <input type="hidden" name="reports[]" value="{{$report->id}}">
                                 <button type="submit" class="btn btn-success" {{$_GET['active'] == 0 ? 'disabled':''}}>Adicionar pagamento</button>
                              </form>
                           </div>
                           @endcan
                        @endif
                     </div>
                     <div class="card-body p-0">
                        <div class="table-responsive">
                           <table class="table card-table table-vcenter text-nowrap datatable">
                              <thead>
                                 <tr>
                                    <th>Período de execução<br>referência / Protocolo</th>
                                    <th>Número da Nota<br> Fiscal/Fatura</th>
                                    <th>Valor da Nota<br> Fiscal/Fatura</th>
                                    <th>Vencimento</th>
                                    <th>Gerado</th>
                                    <th>Valor do Saldo<br> Contratual</th>
                                    @canany(['file-download', 'payment-edit', 'payment-delete'])<th></th>@endcanany
                                 </tr>
                              </thead>
                              <tbody>
                                 @php
                                 if(!empty($report->note->amount))
                                 $saldo_acumulado = $report->note->amount
                                 @endphp
                                 @forelse ($payments as $payment)
                                 @php $saldo_acumulado -= convertFloat($payment->price) @endphp
                                 <tr>
                                    <td><strong>{{reference($payment->reference)}}</strong><br><small class="text-warning">{{$payment->process ? 'ADM-'.$payment->process:'SEM PROTOCOLO'}}</small></td>
                                    <td>{{$payment->invoice}}</td>
                                    <td class="w-1 fw-bold">{{getPrice($payment->price)}}</td>
                                    <td>{{$payment->due_date->format('d/m/Y')}}</td>
                                    <td>{{$payment->signature_date->format('d/m/Y')}}</td>
                                    <td class="w-1 fw-bold {{$saldo_acumulado < 0 ? 'text-danger':''}}">{{getPrice($saldo_acumulado)}}</td>
                                    @canany(['file-download', 'payment-edit', 'payment-delete'])
                                    <td>
                                       <div class="btn-list flex-nowrap">
                                          <div class="dropdown position-static">
                                             <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                             Ações
                                             </button>
                                             <div class="dropdown-menu dropdown-menu-end">
                                                @can('file-download')
                                                <a class="dropdown-item" href="{{route('payments.show', $payment->uuid)}}">
                                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-type-docx" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                      <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                      <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
                                                      <path d="M2 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
                                                      <path d="M17 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0"></path>
                                                      <path d="M9.5 15a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1 -3 0v-3a1.5 1.5 0 0 1 1.5 -1.5z"></path>
                                                      <path d="M19.5 15l3 6"></path>
                                                      <path d="M19.5 21l3 -6"></path>
                                                   </svg>  Fazer download
                                                </a>
                                                @endcan
                                                @can('payment-edit')
                                                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$payment->uuid}}">
                                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                      <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                      <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                      <path d="M16 5l3 3"></path>
                                                   </svg>  Editar
                                                </a>
                                                @endcan
                                                @can('payment-delete')
                                                <a class="dropdown-item text-danger" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete" data-bs-id="{{$payment->uuid}}">
                                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                      <path d="M4 7l16 0"></path>
                                                      <path d="M10 11l0 6"></path>
                                                      <path d="M14 11l0 6"></path>
                                                      <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                      <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                   </svg>  Excluir
                                                </a>
                                                @endcan
                                             </div>
                                          </div>
                                       </div>
                                    </td>
                                    @endcanany
                                 </tr>
                                 @empty
                                 <tr>
                                    <td colspan="7">
                                       <span class="text-warning">
                                       Ops! — Nenhum resultado encontrado!
                                       </span>
                                    </td>
                                 </tr>
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
   </div>
</div>
@endsection
@push('modals')
@can('payment-edit')
{{--! modal payment-edit--}}
<div class="modal modal-blur fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar pagamento</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Número da Nota Fiscal/Fatura</label>
                        <input type="hidden" name="uuid" required>
                        <input type="text" class="form-control"  name="invoice" required>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Período de execução</label>
                        <input type="month" class="form-control"  name="reference" required>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Valor da Nota Fiscal/Fatura</label>
                        <input type="text" class="money form-control"  name="price" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Protocolo</label>
                        <input type="text" class="process-erp form-control"  name="process">
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Vencimento </label>
                        <input type="date" class="form-control"  name="due_date" required>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Data de elaboração</label>
                        <input type="date" class="form-control"  name="signature_date" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Eventuais ocorrências detectadas</label>
                        <textarea name="occurrences[occurrence]" class="form-control" cols="30" rows="3"></textarea>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Eventuais ocorrências/Falhas na execução do contrato</label>
                        <textarea name="occurrences[failures]" class="form-control" cols="30" rows="3"></textarea>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Sugestões de medidas a serem implementadas</label>
                        <textarea name="occurrences[suggestions]" class="form-control" cols="30" rows="3"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Editar pagamento
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('payment-delete')
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
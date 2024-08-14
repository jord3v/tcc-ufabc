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
               Pendências
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
               <div class="form-label">Situação</div>
               <div class="mb-4">
               <div class="form-selectgroup w-100">
                  <a href="{{route('payments.index')}}?active=1" class="form-selectgroup-item w-100 btn text-default">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><path d="M10 12l4 0" /></svg>
                      Ativo</span>
                  </a>
                  <a href="{{route('payments.index')}}?active=0" class="form-selectgroup-item w-100 btn text-default">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 4h11a2 2 0 1 1 0 4h-7m-4 0h-3a2 2 0 0 1 -.826 -3.822" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 1.824 -1.18m.176 -3.82v-7" /><path d="M10 12h2" /><path d="M3 3l18 18" /></svg>
                      Arquivados
                  </a>
                  <a href="{{route('payments.pending')}}" class="form-selectgroup-item w-100 btn text-muted fw-bold" id="pendings-button">
                     carregando
                  </a>
               </div>
               </div>
               @if ($_GET['company'] || $_GET['report'])
                  <div class="mt-5">
                     <a href="{{route('payments.index')}}" class="btn btn-outline-info w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 4h12v2.172a2 2 0 0 1 -.586 1.414l-3.914 3.914m-.5 3.5v4l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227" /><path d="M3 3l18 18" /></svg> Remover filtro
                     </a>
                  </div>
               @endif
               </div>
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
                        </div>
                        <div class="card-actions">
                           <button type="submit" class="dynamic-button btn btn-outline-success" data-base-text="Preencher itens selecionados" disabled>Preencher itens selecionados</button>
                       </div>
                     </div>
                     <div class="card-body p-0">
                        <div class="table-responsive">
                           <table class="table card-table table-vcenter text-nowrap datatable">
                              <thead>
                                  <tr>
                                      <th>Mês</th>
                                      <th>Relatórios sem pagamento</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($pendingPayments as $monthName => $reports)
                                      <tr>
                                          <td>{{ $monthName }}<br> <strong class="text-danger">{{$reports->count() > 0 ? $reports->count().' pendências' : ''}}</strong></td>
                                          <td class="td-truncate">
                                              @if ($reports->isEmpty())
                                                  <span class="text-success">Não há pendências</span>
                                              @else
                                                   @foreach ($reports->groupBy('company.name') as $company => $notes)
                                                      <h3 class="text-warning mb-2">{{$company}}</h3>
                                                      @foreach ($notes as $note)
                                                      
                                                         <label class="form-check">
                                                            <input type="checkbox" class="form-check-input" name="reports[]" value="{{$note->id}}">
                                                            <span class="form-check-label">
                                                               {{$note->note->number}}/{{$note->note->year}} {{$note->location->name}}
                                                            </span>
                                                            <span class="form-check-description">
                                                               {{$note->note->service}}
                                                            </span>
                                                          </label>
                                                      @endforeach
                                                   @endforeach
                                              @endif
                                          </td>
                                      </tr>
                                  @endforeach
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

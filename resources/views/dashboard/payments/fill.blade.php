@php 
   $reportCount = 0;
@endphp
@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle"> </div>
            <h2 class="page-title">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-description"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><path d="M9 17h6"></path><path d="M9 13h6"></path></svg> Relatórios circunstanciados - Preencher
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('reports.index')}}" class="btn">
               Voltar
               </a>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="page-body">
   <div class="container-xl">
      <form action="{{route('payments.store')}}" method="post" class="needs-validation" novalidate="" autocomplete="off">
         @csrf
         <div class="card">
            <div class="card-header">
               <ul class="nav nav-tabs card-header-tabs bg-white" data-bs-toggle="tabs" role="tablist">
                  @foreach ($reports->keys() as $report)
                  <li class="nav-item" role="presentation">
                     <a href="#{{tab_id($report)}}" class="nav-link {{$loop->first ? 'active':''}}" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-skyscraper">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <path d="M3 21l18 0"></path>
                           <path d="M5 21v-14l8 -4v18"></path>
                           <path d="M19 21v-10l-6 -4"></path>
                           <path d="M9 9l0 .01"></path>
                           <path d="M9 12l0 .01"></path>
                           <path d="M9 15l0 .01"></path>
                           <path d="M9 18l0 .01"></path>
                        </svg>
                        {{$report}}
                     </a>
                  </li>
                  @endforeach
               </ul>
               <div class="card-actions">
                  @foreach ($reports as $key => $report)
                     @php
                        $reportCount += count($report);
                     @endphp
                  @endforeach
                  @if($reportCount > 1 )
                     <a href="" class="btn-sm" data-bs-toggle="modal" data-bs-target="#bulk-fill">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-multiple" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" /></svg>
                     Preencher relatórios em massa
                     </a>
                  @endif
                </div>
            </div>
            <div class="card-body p-0">
               <div class="tab-content">
                  @foreach ($reports as $key => $report)
                  <div class="tab-pane fade {{$loop->first ? 'active show':''}}" id="{{tab_id($key)}}" role="tabpanel">
                     <div class="list-group list-group-flush">
                        @foreach ($report as $item)   
                        <div class="list-group-item">
                           <div class="card">
                              <div class="row g-0">
                                 <div class="col-auto">
                                    <div class="card-body">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-description">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                          <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                          <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                          <path d="M9 17h6"></path>
                                          <path d="M9 13h6"></path>
                                       </svg>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <div class="card-body ps-0">
                                       <div class="row">
                                          <div class="col">
                                             <h3 class="mb-0">{{$item->location->name}} - {{$item->note->service}}</h3>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md">
                                             <div class="mt-3 list-inline list-inline-dots mb-0 text-secondary d-sm-block">
                                                <div class="datagrid">
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title">Nota de empenho</div>
                                                      <div class="datagrid-content">{{$item->note->number}}/{{$item->note->year}}</div>
                                                   </div>
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title">Processo SECOM</div>
                                                      <div class="datagrid-content">{{$item->note->process}}</div>
                                                   </div>
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title">Modalidade da licitação e processo</div>
                                                      <div class="datagrid-content">{{$item->note->modality}} - {{$item->note->modality_process}}</div>
                                                   </div>
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title">Elaborado por:</div>
                                                      <div class="datagrid-content">{{$item->manager}} - {{$item->department}}</div>
                                                   </div>
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title">Valor total</div>
                                                      <div class="datagrid-content">{{getPrice($item->note->amount)}}</div>
                                                   </div>
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title">Valor mensal</div>
                                                      <div class="datagrid-content">{{getPrice($item->note->monthly_payment)}}</div>
                                                   </div>
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title">Período</div>
                                                      <div class="datagrid-content">
                                                         <div class="datagrid-content">{{formatPeriod($item->note->start, $item->note->end)}}</div>
                                                      </div>
                                                   </div>
                                                   <div class="datagrid-item">
                                                      <div class="datagrid-title"> </div>
                                                      <div class="datagrid-content">
                                                         <button type="button" class="btn btn-sm btn-outline-primary" onclick="add(event)" data-bs-report="{{$item->id}}">Adicionar nova linha</button>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="table-responsive">
                              <table class="table table-hover table-bordered card-table table-vcenter text-nowrap datatable" id="myTable_{{$item->id}}">
                                 <thead>
                                    <tr>
                                       <th>Número da Nota Fiscal/Fatura <a href="javascript:void(0)" onclick="lastInvoice({{$item->id}})" data-bs-trigger="hover" data-bs-toggle="popover"
                                          title="Repetir o número da fatura anterior" data-bs-content="Só é útil em casos de boletos com o mesmo número de conta. Exemplo: Faturas da Vivo"
                                        ><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-placeholder" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 20.415a8 8 0 1 0 3 -15.415h-3" /><path d="M13 8l-3 -3l3 -3" /><path d="M7 17l4 -4l-4 -4l-4 4z" /></svg></a></th>
                                       <th>Período de execução</th>
                                       <th>Valor da Nota Fiscal/Fatura – R$</th>
                                       <th>Vencimento</th>
                                       <th>Data de elaboração</th>
                                       <th class="text-center">Ação</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr data-id="{{$item->id}}">
                                       <td>
                                            <div class="d-none">
                                                <input type="hidden" name="payments[{{$item->id}}][report_id]" value="{{$item->id}}" required>
                                                <textarea name="payments[{{$item->id}}][occurrences][occurrence]"></textarea>
                                                <textarea name="payments[{{$item->id}}][occurrences][failures]"></textarea>
                                                <textarea name="payments[{{$item->id}}][occurrences][suggestions]"></textarea>
                                            </div>
                                          <input type="text" class="invoice form-control" name="payments[{{$item->id}}][invoice]" required>
                                       </td>
                                       <td>
                                          <input type="month" class="reference form-control" name="payments[{{$item->id}}][reference]" required>
                                       </td>
                                       <td>
                                          <input type="text" class="money form-control" name="payments[{{$item->id}}][price]" required>
                                       </td>
                                       <td>
                                          <input type="date" class="due_date form-control" name="payments[{{$item->id}}][due_date]" required>
                                       </td>
                                       <td>
                                          <input type="date" class="signature_date form-control" name="payments[{{$item->id}}][signature_date]" value="{{now()->format('Y-m-d')}}" required>
                                       </td>
                                       <td>
                                        <div class="row">
                                            <div class="col-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Eventuais ocorrências">
                                                <button type="button" class="btn btn-default btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#occurrences" data-bs-id="{{$item->id}}">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-alert"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17l.01 0" /><path d="M12 11l0 3" /></svg>
                                                </button>
                                            </div>
                                            <div class="col-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover pagamento">
                                                <button type="button" class="btn btn-default btn-icon text-danger" onclick="remove(this)">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        @endforeach
                     </div>
                  </div>
                  @endforeach
               </div>
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
<div class="modal modal-blur fade" id="occurrences" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="modalForm">
         <div class="modal-header">
            <h5 class="modal-title">Eventuais ocorrências</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-lg-12">
                  <div class="mb-3">
                    <input type="hidden" name="occurrence_id" id="occurrence_id">
                    <label class="form-label">Eventuais ocorrências detectadas</label>
                    <textarea class="form-control" rows="3" name="occurrence"></textarea>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-lg-12">
                  <div class="mb-3">
                    <label class="form-label">Eventuais ocorrências/Falhas na execução do contrato</label>
                    <textarea class="form-control" rows="3" name="failures"></textarea>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-lg-12">
                  <div class="mb-3">
                    <label class="form-label">Sugestões de medidas a serem implementadas</label>
                    <textarea class="form-control" rows="3" name="suggestions"></textarea>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancelar
            </a>
            <button type="submit" id="submit" class="btn btn-primary ms-auto">Incluir ocorrência</button>
         </div>
        </form>
      </div>
   </div>
</div>
<div class="modal modal-blur fade" id="bulk-fill" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
     <form>
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title">Preencher em massa</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         <div class="row mb-3">
           <div class="col-lg-4">
            <label class="form-label">Período de execução</label>
            <input type="month" class="form-control" name="reference">
           </div>
           <div class="col-lg-4">
            <label class="form-label">Vencimento</label>
            <input type="date" class="form-control" name="due_date">
           </div>
           <div class="col-lg-4">
            <label class="form-label">Data de elaboração</label>
            <input type="date" class="form-control" name="signature_date" value="{{now()->format('Y-m-d')}}">
           </div>
         </div>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
         <button type="button" class="btn btn-primary" onclick="put()" data-bs-dismiss="modal">Aplicar</button>
       </div>
     </div>
     </form>
   </div>
</div>
@endcan
@endpush
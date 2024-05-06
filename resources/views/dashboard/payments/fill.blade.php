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
               Adicionar pagamentos
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
      @include('layouts.flash-message')
      <form action="{{route('payments.store')}}" method="post" class="needs-validation" novalidate="" autocomplete="off">
        @csrf
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                @foreach ($reports->keys() as $report)
                <li class="nav-item" role="presentation">
                    <a href="#{{tab_id($report)}}" class="nav-link {{$loop->first ? 'active':''}}" data-bs-toggle="tab" aria-selected="true" role="tab"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-skyscraper"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 21l18 0"></path><path d="M5 21v-14l8 -4v18"></path><path d="M19 21v-10l-6 -4"></path><path d="M9 9l0 .01"></path><path d="M9 12l0 .01"></path><path d="M9 15l0 .01"></path><path d="M9 18l0 .01"></path></svg> {{$report}}</a>
                </li>
                @endforeach
                </ul>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-description"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><path d="M9 17h6"></path><path d="M9 13h6"></path></svg>
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
                                            <div class="mt-3 list-inline list-inline-dots mb-0 text-secondary d-sm-block d-none">
                                                <div class="list-inline-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                    <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                                                    </svg>
                                                    Remote / USA
                                                </div>
                                            </div>
                                            <div class="mt-3 list mb-0 text-secondary d-block d-sm-none">
                                                <div class="list-item">
                                                    Detalhes
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="add(event)" data-bs-report="{{$item->id}}">Adicionar nova nota fiscal/fatura</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-hover table-bordered card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Número da Nota Fiscal/Fatura</th>
                                        <th>Período de execução/referência</th>
                                        <th>Valor da Nota Fiscal/Fatura – R$</th>
                                        <th>Vencimento</th>
                                        <th>Data de elaboração</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="payments[{{$item->id}}][report_id]" value="{{$item->id}}" required>
                                            <input type="text" class="form-control" name="payments[{{$item->id}}][invoice]" required>
                                        </td>
                                        <td>
                                            <input type="month" class="form-control" name="payments[{{$item->id}}][reference]" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="payments[{{$item->id}}][price]" required>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control" name="payments[{{$item->id}}][due_date]" required>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control" name="payments[{{$item->id}}][signature_date]" value="{{now()->format('Y-m-d')}}" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn-link" onclick="remove(this)">Remover</button>
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
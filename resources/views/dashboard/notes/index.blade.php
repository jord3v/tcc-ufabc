@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle"> </div>
            <h2 class="page-title">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-receipt"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2"></path></svg> Notas de empenho
            </h2>
         </div>
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
               <a href="{{route('dashboard')}}" class="btn">
               Voltar
               </a>
               @can('note-create')
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#note-create">
               Nova nota de empenho
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
            <h3 class="card-title">Lista de notas empenho</h3>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-25">Objeto</th>
                     <th class="w-25">Modalidade</th>
                     <th>Valor total</th>
                     <th>Valor mensal</th>
                     <th>Período</th>
                     <th>Situação</th>
                     @can('note-edit')<th></th>@endcan
                  </tr>
               </thead>
               <tbody>
                  @forelse ($notes as $note)
                  @php $total = 0 @endphp
                  <tr>
                     <td class="td-truncate">
                        <div class="font-weight-medium text-truncate">{{$note->number}}/{{$note->year}} - {{$note->service}}</div>
                        <div class="text-truncate text-muted">
                           {{$note->comments ? $note->comments : 'N/A'}}
                        </div>
                        @foreach ($note->reports as $report)
                            @php 
                              $total += $report->payments_sum_price; 
                              $percentage = round(($total / $note->amount) * 100);
                            @endphp
                        @endforeach
                        <div class="progress progress-xs">
                           <div class="progress-bar bg-info" style="width: {{$percentage ?? 0}}%"></div>
                        </div>
                     </td>
                     <td>
                       <div class="d-flex py-1 align-items-center">
                         <div class="flex-fill">
                           <div class="font-weight-medium">{{$note->modality}} - {{$note->modality_process}}</div>
                           <div class="text-secondary">Processo SECOM {{$note->process}}</div>
                         </div>
                       </div>
                     </td>
                     <td>{{getPrice($note->amount)}}</td>
                     <td>{{getPrice($note->monthly_payment)}}</td>
                     <td>{{formatPeriod($note->start, $note->end)}}</td>
                     <td>
                        @if ($note->active)
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
                     @can('note-edit')
                     <td>
                        <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#edit" data-bs-id="{{$note->id}}">
                        Editar
                        </a>
                     </td>
                     @endcan
                   </tr>
                  @empty
                  @endforelse
               </tbody>
               {{--<tbody>
                  @foreach ($notes as $note)
                    <td class="td-truncate">
                     <div class="font-weight-medium">{{$note->service}}</div>
                     <div class="text-truncate">
                        {{$note->comments ? $note->comments : 'N/A'}}
                     </div>
                    </td>
                    <td class="text-nowrap text-secondary">28 Nov 2019</td>
                    <td class="text-nowrap text-secondary">28 Nov 2019</td>
                    <td class="text-nowrap text-secondary">28 Nov 2019</td>
                    <td class="text-nowrap text-secondary">28 Nov 2019</td>
                    <td class="text-nowrap text-secondary">28 Nov 2019</td>
                  </tr>
                  @endforeach
               </tbody>--}}
            </table>
         </div>
         <div class="card-footer">
            {{ $notes->withQueryString()->links() }}
         </div>
      </div>
   </div>
</div>
@endsection
@push('modals')
@can('note-create')
{{--! modal note-create--}}
<div class="modal modal-blur fade" id="note-create" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form action="{{route('notes.store')}}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="modal-header">
               <h5 class="modal-title">Nova nota de empenho</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-9">
                     <div class="mb-3">
                        <label class="form-label">Número da nota/ANO</label>
                        <div class="input-group">
                           <input type="number" name="number" class="form-control">
                           <select name="year" class="form-control">
                              <option value="">Selecione</option>
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024" selected>2024</option>
                              <option value="2025">2025</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-3">
                     <div class="mb-3">
                        <label class="form-label">Processo SECOM</label>
                        <input type="text" class="process form-control" name="process" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-8">
                     <div class="mb-3">
                        <label class="form-label">Modalidade da licitação</label>
                        <select class="form-select" name="modality" required>
                           <option value="">Selecione a modalidade</option>
                           <option value="Pregão Eletrônico">Pregão Eletrônico</option>
                           <option value="Dispensa Eletrônica">Dispensa Eletrônica</option>
                           <option value="Não se aplica">Não se aplica</option>
                           <option value="Outros">Outros</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Processo</label>
                        <input type="text" class="process form-control" name="modality_process" required>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Objeto</label>
                        <input type="text" class="form-control" name="service" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Valor total</label>
                        <input type="text" class="money form-control" name="amount" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Valor mensal</label>
                        <input type="text" class="money form-control" name="monthly_payment" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Data inicial</label>
                        <input type="date" class="form-control" name="start" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Data final</label>
                        <input type="date" class="form-control" name="end" required>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div>
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="comments" rows="3"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Nova nota de empenho
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('note-edit')
{{--! modal note-edit--}}
<div class="modal modal-blur fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <form method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
               <h5 class="modal-title">Editar nota de empenho</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-9">
                     <div class="mb-3">
                        <label class="form-label">Número da nota/ANO</label>
                        <div class="input-group">
                           <input type="number" name="number" class="form-control">
                           <select name="year" class="form-control">
                              <option value="">Selecione</option>
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024">2024</option>
                              <option value="2025">2025</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-3">
                     <div class="mb-3">
                        <label class="form-label">Processo SECOM</label>
                        <input type="text" class="process form-control" name="process" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-8">
                     <div class="mb-3">
                        <label class="form-label">Modalidade da licitação</label>
                        <select class="form-select" name="modality" required>
                           <option value="">Selecione a modalidade</option>
                           <option value="Pregão Eletrônico">Pregão Eletrônico</option>
                           <option value="Dispensa Eletrônica">Dispensa Eletrônica</option>
                           <option value="Não se aplica">Não se aplica</option>
                           <option value="Outros">Outros</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="mb-3">
                        <label class="form-label">Processo</label>
                        <input type="text" class="process form-control" name="modality_process" required>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="mb-3">
                        <label class="form-label">Objeto</label>
                        <input type="text" class="form-control" name="service" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Valor total</label>
                        <input type="text" class="money form-control" name="amount" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Valor mensal</label>
                        <input type="text" class="money form-control" name="monthly_payment" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Data inicial</label>
                        <input type="date" class="form-control" name="start" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="mb-3">
                        <label class="form-label">Data final</label>
                        <input type="date" class="form-control" name="end" required>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div>
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="comments" rows="3"></textarea>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <div class="mt-3">
                        <div class="form-label">Ativo</div>
                        <label class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" checked>
                        <span class="form-check-label"></span>
                        </label>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
               Cancelar
               </a>
               <button type="submit" class="btn btn-primary ms-auto">
               Editar nota de empenho
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endcan
@can('note-delete')
<div class="modal modal-blur fade" id="note-delete" tabindex="-1" role="dialog" aria-hidden="true">
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
@push('scripts')
@endpush
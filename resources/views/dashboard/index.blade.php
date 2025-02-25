@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle"> </div>
            <h2 class="page-title">
               <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dashboard" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                  <path d="M13.45 11.55l2.05 -2.05"></path>
                  <path d="M6.4 20a9 9 0 1 1 11.2 0z"></path>
               </svg> Painel administrativo
            </h2>
         </div>
      </div>
   </div>
</div>
<div class="page-body">
   <div class="container-xl">
      <div class="row row-deck row-cards mb-3">
         @canany(['company-list', 'report-list', 'note-list', 'location-list'])
         <div class="col-md-6 col-lg-4">
            <div class="row row-cards">
               @can('company-list')
              <div class="col-12">
               <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader">
                        <a href="{{route('companies.index')}}">Prestadores de serviços</a>
                      </div>
                    </div>
                    <div class="h1 mb-3">{{$companies->count()}}</div>
                  </div>
                </div>
              </div>
              @endcan
              @can('note-list')
              <div class="col-12">
               <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader">
                        <a href="{{route('notes.index')}}">Notas de empenhos</a>
                      </div>
                    </div>
                    <div class="h1 mb-3">{{$notes->count()}}</div>
                  </div>
                </div>
              </div>
              @endcan
              @can('location-list')
              <div class="col-12">
               <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader">
                        <a href="{{route('locations.index')}}">Unidades operacionais</a>
                     </div>
                    </div>
                    <div class="h1 mb-3">{{$locations->count()}}</div>
                  </div>
                </div>
              </div>
              @endcan
              @can('report-list')
              <div class="col-12">
               <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader">
                        <a href="{{route('reports.index')}}">Relatórios circunstanciados</a>
                     </div>
                    </div>
                    <div class="h1 mb-3">{{$reports->count()}}</div>
                  </div>
                </div>
              </div>
              @endcan
            </div>
         </div>
         @endcanany
         @can('payment-list')
         <div class="col-lg-8">
           <div class="card">
             <div class="card-header border-0">
               <div>
                 <h3 class="card-title">
                  Histórico de pagamentos
                 </h3>
                 <p class="card-subtitle">
                   Últimos {{count($data['months'])}} meses
                 </p>
               </div>
               <div class="card-actions">
                 <a href="{{route('payments.index')}}" class="btn btn-outline-primary">
                   Consultar pagamentos
                 </a>
                 <a href="{{route('payments.pending')}}" id="pendings-button" class="btn btn-outline-warning">carregando</a>
               </div>
             </div>
             <div class="card-body p-0">
               <div id="chart"></div>
             </div>
           </div>
         </div>
         @endcan
      </div>
      @can('note-list')
      <div class="row row-deck row-cards" id="cardContainer">
        <div class="col-12 p-3">
          <h2 class="page-title">
            <a href="{{route('notes.index')}}">Notas de empenho em vigor</a>
          </h2>
        </div>
        @forelse ($notes as $note)
        @php $total = 0 @endphp
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col text-truncate" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{$note->service}}">
                  <small class="text-primary fw-bold text-uppercase"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-skyscraper"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 21l18 0"></path><path d="M5 21v-14l8 -4v18"></path><path d="M19 21v-10l-6 -4"></path><path d="M9 9l0 .01"></path><path d="M9 12l0 .01"></path><path d="M9 15l0 .01"></path><path d="M9 18l0 .01"></path></svg> {{ $note->reports->pluck('company.name')->unique()->implode(', ') ?: 'nota não associada' }}</small>
                  <h3 class="card-title mb-1">{{$note->number}}/{{$note->year}} - {{$note->modality}}</h3>
                  <small class="text-truncate text-muted text-uppercase">{{$note->service}}</small>
                  <div class="mt-3">
                    <div class="row g-2 align-items-center">
                      <div class="col">
                        <div class="d-flex mb-2">
                          @foreach ($note->reports as $report)
                            @php 
                              $total += $report->payments_sum_price; 
                              $percentage = round(($total / $note->amount) * 100);
                            @endphp
                          @endforeach
                          <span class="fw-bold text-primary text-center text-uppercase text-center">{{ $total != 0 ? 'Valor usado: '.getPrice($total) : 'Nenhum boleto lançado' }}</span>
                        </div>
                        <div class="progress progress-xs">
                          <div class="progress-bar bg-info" style="width: {{ $total != 0 ? $percentage : 0 }}%"></div>
                       </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>    
        @empty
            
        @endforelse
      </div>
      @endcan
   </div>
</div>
@endsection
@push('scripts')
<script>
   var options = {
    series: [{
    name: 'Lançamento de boletos',
    data: @json(array_values($data['sum']))
  }],
    chart: {
    height: 350,
    type: 'area',
    toolbar: {
      show: true,
    },
  },
  plotOptions: {
    bar: {
      borderRadius: 10,
      dataLabels: {
        position: 'top', // top, center, bottom
      },
    }
  },
  dataLabels: {
    enabled: false,
    formatter: function (val) {
      var valNumber = parseFloat(val);
      if (!isNaN(valNumber)) {
        var valorFormatado = valNumber.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }
      return "R$ "+valorFormatado;
    },
    offsetY: -20,
    style: {
      fontSize: '12px',
      colors: ["#304758"]
    }
  },
  
  xaxis: {
    categories: @json(array_values($data['months'])),
    position: 'bottom',
    axisBorder: {
      show: true
    },
    axisTicks: {
      show: true
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    tooltip: {
      enabled: true,
    }
  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: true,
    },
    labels: {
      show: true,
      formatter: function (val) {
        var valNumber = parseFloat(val);
        if (!isNaN(valNumber)) {
          var valorFormatado = valNumber.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        return "R$ "+valorFormatado;
      }
    }
  
  },
  };

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();
</script>
@endpush
@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle"> </div>
            <h2 class="page-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-history"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 8l0 4l2 2"></path><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"></path></svg> Registro de atividades
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
            <h3 class="card-title">Usuarios do sistema</h3>
         </div>
         <div class="card-body">
            <div class="divide-y">
                @foreach ($activities as $activity)
                <div>
                    <div class="row">
                      <div class="col-auto">
                        <span class="avatar" style="background-image: url({{avatar($activity->causer->name)}})"></span>
                      </div>
                      <div class="col">
                        <div class="text-truncate">
                           <a href="#" class="view-activity" data-id="{{ $activity->id }}" data-bs-toggle="modal" data-bs-target="#activities">
                              {!!$activity->description!!}
                           </a>
                        </div>
                        <div class="text-secondary">{{$activity->created_at->diffForHumans()}}</div>
                      </div>
                    </div>
                  </div>      
                @endforeach
            </div>
          </div>
         <div class="card-footer">
            {{ $activities->links() }}
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="activities" tabindex="-1" aria-labelledby="activitiesLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="activitiesLabel">Detalhes da Atividade</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <div id="modalContent">
                   <p>Carregando...</p>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection
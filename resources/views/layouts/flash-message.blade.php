@if ($message = session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
   <div class="d-flex">
      <div>
         <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M5 12l5 5l10 -10"></path>
         </svg>
      </div>
      <div>
         <h4 class="alert-title">Sucesso!</h4>
         <div class="text-secondary">{{ $message }}</div>
      </div>
   </div>
   <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
   <div class="d-flex">
      <div>
         <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <circle cx="12" cy="12" r="9"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
         </svg>
      </div>
      <div>
         <h4 class="alert-title">Verifique se há erros no formulário abaixo</h4>
         <div class="text-secondary">
            <ul>
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      </div>
   </div>
   <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
@endif
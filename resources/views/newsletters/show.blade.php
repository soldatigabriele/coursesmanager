@extends('layouts.forms')

@section('style')
<style type="text/css" media="screen">
.subtitle{
  font-weight: 800;
}
.righe>div{
  text-align: left;
  border-bottom: 1px solid #eee;
}
.elements{
  padding-top: 3px;
  font-size:14pt;
}
.value{
  font-weight: 800
}
.success-message{
    background: #d4edda;
    color: #155724;
    padding: 6px; 
    border-radius: 6px;
}
</style>
@endsection

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10 col-sm-12">
      <div class="card">
        <div class="card-header"><b>RIASSUNTO UTENTE {{ strtoupper($newsletter->name) }} {{ strtoupper($newsletter->surname) }}</b></div>
        <div class="card-body">
          @if (!$message = Session::get('status'))
              <h3>{{ $message }}</h3>
          @endif
          <div class="col alert alert-success">
            
            <h4>Controlla i tuoi dettagli:</h4>
            
            <div class="col-12 elements">Nome: <span class="value">{{ $newsletter->name }}</span></div>
            <div class="col-12 elements">Cognome: <span class="value">{{ $newsletter->surname }}</span></div>
            <div class="col-12 elements">Email: <span class="value">{{ $newsletter->email }}</span></div>
            <div class="col-12 elements">Provenienza: <span class="value">{{ $newsletter->region['name'] }}</span></div>

          </div>

          <div class="col">
            Se noti qualche errore, manda una mail a casadipaglia@hotmail.com
          </div>
          
          <div class="clearfix"></div><br>
          <hr>
            <a role="button" href="http://www.laboa.org" class="btn btn-success">Torna a Laboa.org</a>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

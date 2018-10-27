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
        <div class="card-header"><b>RIASSUNTO UTENTE {{ strtoupper($partecipant->name) }} {{ strtoupper($partecipant->surname) }}</b></div>
        <div class="card-body">
          @if (!$message = Session::get('status'))
              <h3>{{ $message }}</h3>
          @endif
          <div class="col alert alert-primary">
            
            <h4>Controlla i tuoi dettagli:</h4>
            
            <div class="col-12 elements">Nome: <span class="value">{{ $partecipant->name }}</span></div>
            <div class="col-12 elements">Cognome: <span class="value">{{ $partecipant->surname }}</span></div>
            <div class="col-12 elements">Email: <span class="value">{{ $partecipant->email }}</span></div>
            <div class="col-12 elements">Telefono: <span class="value">{{ $partecipant->phone }}</span></div>
            <div class="col-12 elements">Provenienza: <span class="value">{{ $partecipant->getData()->city }}</span></div>

          </div>
          <div class="col">
            Se noti qualche errore, manda una mail a casadipaglia@hotmail.com
          </div>
          <br>
          @if($coupon = $partecipant->personalCoupon)
            <div class="col alert alert-success">
              <h3>BUONO SCONTO</h3>
              <p> 
              Invita altre persone a iscriversi: per ogni registrazione effettuata con il tuo codice, riceverai uno sconto di <span style="font-weight:600;font-size:120%;">10€</span> sulla quota di partecipazione al presente seminario (non fruibile su altri corsi). 
              Chi si iscriverà con il tuo codice riceverà a sua volta uno sconto di <span style="font-weight:600;font-size:120%;">10€</span>.
              </p>
              <p>
                Il tuo codice è: <span style="font-weight:600;border:solid 1px green;padding:3px;border-radius:4px;"> {{ $coupon->value }} </span>
              </p>
            </div>
          @endif

          <div class="clearfix"></div><br>
          <div class="col">
          @foreach($partecipant->courses as $course)
            @if($course->long_id)
              <h3>Corso:</h3>  
              <div class="col-12 elements">Codice Corso: <span class="value">{{ $course->long_id }}</span></div>
              <div class="col-12 elements">Descrizione: <span class="value">{{ $course->description }}</span></div>
              <div class="col-12 elements">Date: <span class="value">{{ $course->date }}</span></div>
              @if($couponApplied = $partecipant->getCoupon())
              <div class="col-12 elements">Coupon utilizzato: <span class="value">{{ $couponApplied }}</span></div>
              @endif
            @endif
          @endforeach
          </div>
          <div class="clearfix"></div><br>
          <hr>
            @auth
              <a role="button" href="{{ route('home') }}" class="btn btn-secondary">Indietro</a>
            @else
              <a role="button" href="http://www.laboa.org" class="btn btn-success">Torna a Laboa.org</a>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

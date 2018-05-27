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
.subtitle{
  font-weight: 800;
}
.righe>div{
  text-align: left;
  border-bottom: 1px solid #eee;
}
</style>
@endsection

@section('content')
<br>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-header"><b>ISCRIZIONE ALLA NEWSLETTER LABOA.ORG</b></div>
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          @php
          if(env('APP_ENV')==='local')
          {
              $name = 'Test';
              $surname = 'User';
              $email = 'test@gmail.com';
          }
          @endphp
          <form action="{{ route('newsletter.store') }}" method="post">
                {{ csrf_field() }}
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-sm-offset-2 col-md-offset-3">
                  
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <div class="form-group">
                        <label>Nome:</label>
                        <input type="text" name="name" id="name" class="form-control input-lg" placeholder="" value="{{ old('name') }} @php if(isset($name)){ echo $name;} @endphp">
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <div class="form-group">
                        <label>Cognome:</label>
                        <input type="text" name="surname" id="surname" class="form-control input-lg" placeholder="" value="{{ old('surname') }} @php if(isset($surname)){ echo $surname;} @endphp">
                      </div>
                    </div>
                  </div>

                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label>Email:</label>
                          <input type="text" class="form-control" name="email" value="{{ old('email') }} @php if(isset($email)){ echo $email;} @endphp">
                        </div>
                      </div>
                    
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label>Ripeti Email:</label>
                          <input type="text" class="form-control" name="email_again" id="email_again" value="{{ old('email_again') }} @php if(isset($email)){ echo $email;} @endphp">
                        </div>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <div class="form-group">
                        <label>Regione:</label>
                        <select class="form-control" name="region_id">
                          <option value="empty"> - </option>
                          @foreach($regions as $region)
                            <option value="{{ $region->id }}" @if(old('region_id') == $region->id)selected @endif>{{ $region->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-xs-10 col-sm-10 col-md-10">
                      {!! NoCaptcha::display() !!}
                    </div>
                  </div>
               </div>
                <div class="col-xs-8 col-sm-9 col-md-12">
                 Cliccando su <strong class="label label-primary">Iscrivimi alla Newsletter</strong>, accetti i <a href="#" data-toggle="modal" data-target="#t_and_c_m">Termini e le condizioni</a>, compresi l'utilizzo dei cookie.
                </div>
                 <hr class="colorgraph">
                 <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                    <input class="btn btn-lg btn-success" name="subscribe" type="submit" value="Iscrivimi alla newsletter"/>

                  </div>
                </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">


$(document).ready(function(){
   $('#email_again').on("cut copy paste",function(e) {
      e.preventDefault();
   });
});



</script>

@endsection

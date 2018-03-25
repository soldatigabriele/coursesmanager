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
          
          <form action="{{ route('newsletter-store') }}" method="post">
                {{ csrf_field() }}
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-sm-offset-2 col-md-offset-3">
                  
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <div class="form-group">
                        <label>Nome:</label>
                        <input type="text" name="name" id="name" class="form-control input-lg" placeholder="" value="{{ old('name') }}">
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <div class="form-group">
                        <label>Cognome:</label>
                        <input type="text" name="surname" id="surname" class="form-control input-lg" placeholder="" value="{{ old('surname') }}">
                      </div>
                    </div>
                  </div>

                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label>Email:</label>
                          <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                        </div>
                      </div>
                    
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label>Ripeti Email:</label>
                          <input type="text" class="form-control" name="email_again" value="{{ old('email_again') }}">
                        </div>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <div class="form-group">
                        <label>Regione:</label>
                        <select class="form-control" name="region">
                          <option value="empty"> - </option>
                          @foreach($regions as $region)
                            <option value="{{ $region->id }}" @if(old('region') == $region->id)selected @endif>{{ $region->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                    
                    <div class="row">
                      <div class="col-xs-8 col-sm-9 col-md-9">
                        {!! app('captcha')->display() !!}
                      </div>
                      <div class="col-xs-8 col-sm-9 col-md-9">
                       Cliccando su <strong class="label label-primary">Iscrivimi alla Newsletter</strong>, accetti i <a href="#" data-toggle="modal" data-target="#t_and_c_m">Termini e le condizioni</a>, compresi l'utilizzo dei cookie.
                     </div>
                   </div>

                   <hr class="colorgraph">
                   <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                      <input name="subscribe" class="btn btn-lg btn-success" type="submit" value="Iscrivimi alla Newsletter"/>
                    </div>
                  </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
$(document).ready(function(){

$(".decimals").keydown(function (event) {
      if (event.shiftKey === true) {
          event.preventDefault();
      }

      if ((event.keyCode >= 48 && event.keyCode <= 57) ||
          (event.keyCode >= 96 && event.keyCode <= 105) ||
          event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
          event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

      } else {
          event.preventDefault();
      }

  });
});

</script>

@endsection

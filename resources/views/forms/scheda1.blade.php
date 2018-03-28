@extends('forms.create')

@section('style')
<style type="text/css" media="screen">
.subtitle{
  font-weight: 800;
}
.righe>div{
  text-align: left;
  border-bottom: 1px solid #eee;
}
</style>
@endsection

@section('formcontent')

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-sm-offset-2 col-md-offset-3">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Nome:</label>
            <input type="text" name="name" id="name" class="form-control input-lg" placeholder="" value="{{ old('name') }}">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Cognome:</label>
            <input type="text" name="surname" id="surname" class="form-control input-lg" placeholder="" value="{{ old('surname') }}">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Arrivo da:</label>
            <input type="text" name="city" id="city" class="form-control input-lg" placeholder="" value="{{ old('city') }}">
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
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
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Con: (auto/mezzi pubblici)</label>
            <select name="transport" class="form-control">
              <option value="auto" @if(old('transport') == 'auto')selected @endif>Automobile</option>
              <option value="treno" @if(old('transport') == 'treno')selected @endif>Treno</option>
              <option value="autobus" @if(old('transport') == 'autobus')selected @endif>Autobus</option>
              <option value="moto" @if(old('transport') == 'moto')selected @endif>Moto</option>
              <option value="bicicletta" @if(old('transport') == 'bicicletta')selected @endif>Bicicletta</option>
            </select>                        </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Ho saputo del corso da:</label>
              <select name="source" class="form-control" >
                <option value="Sito"  @if(old('source') == 'Sito')selected @endif>Sito LaBoa</option>
                <option value="Facebook"  @if(old('source') == 'Facebook')selected @endif>Facebook</option>
                <option value="Amici"  @if(old('source') == 'Amici')selected @endif>Amici</option>
                <option value="Altro"  @if(old('source') == 'Altro')selected @endif>Altro</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Email:</label>
              <input type="text" class="form-control" name="email" value="{{ old('email') }}">
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Ripeti email:</label>
              <input type="text" class="form-control" name="email_again" id="email_again" value="{{ old('email_again') }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Recapito telefonico:</label>
              <input type="text" class="form-control decimals" name="phone" value="{{ old('phone') }}">
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Professione:</label>
              <input type="text" class="form-control" name="job" value="{{ old('job') }}">
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Corso:</label>
              <select name="course_id" class="form-control">
                <option value="empty"> - </option>
                @foreach($courses as $c)
                <option value="{{$c->id}}" @if(old('course_id') == $c->id)selected @endif>{{$c->long_id}} - {{$c->description}} - {{$c->date}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Preferenze cibo</label>
              <select name="food" class="form-control" >
                <option value="Onnivoro"  @if(old('source') == 'Onnivoro')selected @endif>Onnivoro</option>
                <option value="Vegetariano"  @if(old('source') == 'Vegetariano')selected @endif>Vegetariano</option>
                <option value="Vegano"  @if(old('source') == 'Vegano')selected @endif>Vegano</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="col-xs-10 col-sm-10 col-md-10">
              <label>Sono d'accordo a condividere con gli altri corsisti telefono ed email per organizzare i trasporti ?</label>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 form-group">
              <select name="shares" class="form-control">
                <option value="si">si</option>
                <option value="no">no</option>
              </select>
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

        <div class="row">
          <div class="col-md-12">
            <div class="col-xs-8 col-sm-9 col-md-12">
             Cliccando su <strong class="label label-primary">Completa Iscrizione</strong>, accetti i <a href="#" data-toggle="modal" data-target="#t_and_c_m">Termini e le condizioni</a>, compresi l'utilizzo dei cookie.
           </div>
         </div>
       </div>
       <hr class="colorgraph">
       <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <input class="g-recaptcha btn btn-lg btn-success" data-sitekey="{{ env('INVISIBLE_RECAPTCHA_SITEKEY') }}" data-callback="onSubmit"
          name="subscribe" type="submit" value="Completa Iscrizione"/>
        </div>
      </div>
  </div>
</div>

<br/>
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
   $('#email_again').on("cut copy paste",function(e) {
      e.preventDefault();
   });
});

</script>

@endsection

@extends('forms.create')

@section('style')

@php

$coupon = session()->get('coupon');

$disabled = '';
$readonly = '';
if(session()->has('coupon')){
  $couponApplied = true;
  $readonly = 'readonly';
  $disabled = 'disabled';
  $display = 'display:true;';
}

@endphp

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

<div class="row mb-3">
  <div class="col-12">
    I campi contrassegnati dall'* sono obbligatori
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-sm-offset-2 col-md-offset-3">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Nome *</label>
            <input type="text" name="name" autocomplete="name" id="name" class="form-control input-lg" placeholder="" value="{{ old('name') }}" required>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Cognome *</label>
            <input type="text" name="surname" autocomplete="surname" id="surname" class="form-control input-lg" placeholder="" value="{{ old('surname') }}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Professione *</label>
            <input type="text" class="form-control" name="job" value="{{ old('job') }}" required>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
          <div class="form-group">
            <label>Arrivo da *</label>
            <input type="text" name="city" id="city" class="form-control input-lg" placeholder="città" value="{{ old('city') }}" required>
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
          <div class="col-xs-12 col-sm-12 col-md-6">
          </div> 
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Scelgo l’opzione:</label>
              <select name="formula" class="form-control" {{ $disabled }}>
                <option value="1 corso" selected>corso + pranzi 550€</option>
                <option value="2 cene">corso + pranzi + cene 750€</option>
                <option value="3 tenda">corso + vitto + alloggio in tenda 850€</option>
                <option value="4 camera">corso + vitto + alloggio in camera 950€</option>
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
        
        <hr>
        
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Email *</label>
              <input type="text" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Ripeti email *</label>
              <input type="text" class="form-control" name="email_again" id="email_again" value="{{ old('email_again') }}" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Recapito telefonico *</label>
              <input type="text" class="form-control decimals" name="phone" value="{{ old('phone') }}" required>
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label>Indirizzo di fatturazione *</label>
              <input type="text" name="billing_address" id="billing_address" class="form-control input-lg" placeholder="" value="{{ old('billing_address') }}" required>
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4">
            <div class="form-group">
              <label>Codice Fiscale *</label>
              <input type="text" class="form-control" name="codice_fiscale" value="{{ old('codice_fiscale') }}" maxlength="16" required>
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4">
            <div class="form-group">
              <label>P IVA:</label>
              <input type="text" class="form-control decimals" name="p_iva" value="{{ old('p_iva') }}" maxlength="11">
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4">
            <div class="form-group">
              <label>Codice Univoco:</label>
              <input type="text" class="form-control decimals" placeholder="per fattura elettronica" name="codice_univoco" value="{{ old('codice_univoco') }}" maxlength="11">
            </div>
          </div>


          <div class="col-12">
            <div class="form-group">
              <label>Note:</label>
              <input type="text" class="form-control" placeholder="Note (opzionali)" name="notes" value="{{ old('notes') }}">
            </div>
          </div>

        </div>
        <input type="hidden" id="course-copy" name="course_id" value="103">
        <input type="hidden" name="create_coupon" value="true">
        <div class="row">
          <div class="col-xs-8 col-sm-9 col-md-9" id="coupon-outer-container" style="{{ $display ?? 'display:none;' }}">
            <label for="coupons-checkbox">
                Sono in possesso di un <strong class="label label-primary">Codice Sconto</strong>
            </label>
            <input type="checkbox" id="coupons-checkbox" {{ $disabled ? 'checked disabled' : '' }}/>
            <div class="row" id="coupon-container" style="{{ $display ?? 'display:none;' }}">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <input id="coupon-field" class="form-control" value="{{ session()->get('coupon') }}" maxlength="10" {{ $readonly}}>
                </div>
                <div class="col-md-4 col-xs-4 col-sm-4">
                    <input type="button" id="apply-coupon" class="btn btn-md btn-{{ $disabled ? 'success' : 'primary' }}" value="{{ $disabled ? 'Applicato' : 'Applica Codice' }}" {{ $disabled }}>
                </div>
                <div class="col-md-2 col-xs-2 col-sm-2">
                    <input type="button" id="unset-coupon" class="btn btn-md btn-outline-dark" value="Rimuovi" {{ $disabled ? '' : 'hidden' }}>
                </div>
            </div>
          </div>
       </div>

        <div class="row">
            <div class="col-xs-10 col-sm-10 col-md-10">
              <label>Sono d'accordo a condividere con gli altri corsisti telefono ed email per organizzare i trasporti ?</label>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 form-group">
              <select name="shares" class="form-control">
                <option value="1">si</option>
                <option value="0">no</option>
              </select>
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
          <div class="col-xs-8 col-sm-9 col-md-9">
           Cliccando su <strong class="label label-primary">Completa Iscrizione</strong>, accetti l'utilizzo dei 
           <a href="https://www.laboa.org/condizioni-generali-pdc-malarbe">Termini e condizioni</a>, compresi l'utilizzo dei cookie. 
          </div>
         </div>
       </div>

       <hr class="colorgraph">
       <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <input class="btn btn-lg btn-success" name="subscribe" type="submit" value="Completa Iscrizione"/>
        </div>
      </div>
  </div>
</div>

<br/>
@endsection
         
@section('scripts')

<script type="text/javascript">
$(document).ready(function(){
  
  /**
   * Unset the coupon in session
   *
   * @return void
   */
  function unsetCoupon(){
    $.ajax({
      url: "{{ route('coupon.unset') }}",
    }).done(function(response) {
        if(response.status == 'ok'){
            $("#apply-coupon").attr('class', 'btn btn-md btn-warning');
            $("#apply-coupon").val('Coupon rimosso');
            $("#coupon-field").removeAttr('readonly');
            $('#coupons-checkbox').removeAttr('disabled');
            $('#course').removeAttr('disabled');
            $('#unset-coupon').hide();
            $('#coupon-field').val('');
            $('#course').val('empty');
            function reset (){
                $("#apply-coupon").attr('class', 'btn btn-md btn-outline-primary');
                $("#apply-coupon").val('Applica Coupon');
                $("#apply-coupon").removeAttr('disabled');
            }
            setTimeout(reset, 2000);
        }
      });
  }

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

    $('#coupons-checkbox').click(function(){
        $('#coupon-container').toggle();
    });

    // Upper case
    $('#coupon-field').keyup(function(e){
      $('#coupon-field').val (function () {
        return this.value.toUpperCase();
      })
    });

    let course = null;

    $('#course').change(function(){
      if($(this).val()!== "empty"){
        $('#coupon-outer-container').show();
        course = $(this).val();
        $('#course-copy').val(course)
      }else{
        $('#coupon-outer-container').hide();
        course = null;
      }
    });

    $('#apply-coupon').click(function(e){
        e.preventDefault();
        $.ajax({
          url: "{{ route('coupon.check') }}",
          data: {'coupon': $('#coupon-field').val(), 'course_id': course},
        }).done(function(response) {
            if(response.status == 'ok'){
                $("#apply-coupon").attr('class', 'btn btn-md btn-success');
                $("#apply-coupon").val('Coupon applicato');
                $("#apply-coupon").attr('disabled', 'disabled');
                $("#coupon-field").attr('readonly', 'readonly');
                $('#coupons-checkbox').attr('disabled', 'disabled');
                $('#course').attr('disabled', 'disabled');
                $('#unset-coupon').removeAttr('hidden');
            }
            if(response.status == 'ko'){
                $("#apply-coupon").attr('class', 'btn btn-md btn-danger');
                $("#apply-coupon").val('Coupon errato');
                $("#apply-coupon").attr('disabled', 'disabled');
                function reset (){
                    $("#apply-coupon").attr('class', 'btn btn-md btn-outline-primary');
                    $("#apply-coupon").val('Applica Coupon');
                    $("#apply-coupon").removeAttr('disabled');
                }
                setTimeout(reset, 2000);
            }
        });
    });

    $('#unset-coupon').click(function(e){
      e.preventDefault();
      // Delete the coupon from the session
      unsetCoupon();
    });
});

</script>

@endsection

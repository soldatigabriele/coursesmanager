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
</style>
@endsection

@section('content')
<br>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10 col-sm-12">
      <div class="card">
        <div class="card-header"><b>CORSO {{\App\Course::find($course_id)->long_id}}</b></div>
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
          
          <form action="{{ route('questions-store') }}" method="post" id='i-recaptcha'>
            {{ csrf_field() }}
            
            <input type="hidden" name="courseId" value="{{$course_id}}">
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
                    <!-- qn means Question number -->
                    @for ($i = 1; $i <= $qn; $i++)
                    <div class="col-xs-12 col-sm-12 col-md-6">
                      <div class="form-group">
                        <label>Domanda {{$i}}:</label>
                        <input type="text" class="form-control" placeholder="max 150 caratteri" name="question-{{$i}}" value="{{ old('question-' . $i) }}" maxlength="150">
                      </div>
                    </div>
                    @endfor

                  </div>
                  @if($feed)
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                          <label>Cosa mi è piaciuto:</label>
                          <input type="text" class="form-control decimals"  placeholder="max 250 caratteri" name="feedback-1" value="{{ old('feedback-1') }}" maxlength="300">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                          <label>Ancora meglio se:</label>
                          <input type="text" class="form-control decimals"  placeholder="max 250 caratteri" name="feedback-2" value="{{ old('feedback-2') }}" maxlength="300">
                        </div>
                      </div>
                      @endif

                      
                    <div class="row">
                      <div class="col-md-12">
                        <div class="col-xs-10 col-sm-10 col-md-10">
                          {!! NoCaptcha::display() !!}
                        </div>
                      </div>
                  </div>

                  <hr class="colorgraph">
                  <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                      <input class="btn btn-lg btn-success" name="subscribe" type="submit" value="Invia"/>
                    </div>
                  </div>
              </div>
            </div>

            <br/>


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

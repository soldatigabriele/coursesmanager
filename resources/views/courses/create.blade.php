@extends('layouts.app')

@section('style')
<style type="text/css" media="screen">
    .subtitle{
        font-weight: 800;
    }
    .righe>div{
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .codice_corso{
      position: relative;
      top: 7px;
    }
</style>
@endsection

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
      <div class="clearfix"></div><br>
      <div class="card">
        <div class="card-header">INSERIMENTO NUOVO CORSO</div>
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
          <div class="container">
            <form action="{{ route('courses.store') }}" method="post" id="formprova" name="a">
              @csrf
              <div class="row">
                <div class="col">
                  <label> Nome Corso </label>
                  <input class="form-control" type="text" name="description" value="{{ old('description') }}"/>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label> Data corso </label>
                  <input class="form-control" type="text" name="date" value="{{ old('date') }}" />
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="start_date">Data inizio</label>
                  <input class="datepicker form-control" name="start_date" width="276">
                </div>
                <div class="col">
                  <label for="end_date">Data fine</label>
                  <input class="datepicker form-control" name="end_date" width="276">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label> Numero iscritti (indicativo)</label>
                  <select name="limit" class="form-control">
                   @php
                   for($i=5;$i<20;$i++){echo '<option value="'.$i.'">'.$i.'</option>';}
                    @endphp
                   <option value="20" selected="selected">20</option>
                   <option value="99">illimitato</option>
                 </select>
               </div>

               <div class="col codice_corso">
                CODICE CORSO: (max 10 caratteri)
                <input id="long_id" class="cod form-control" type="text" name="long_id" maxlength="10" value="{{ old('long_id')}}" />
              </div>
            </div>
            <div class="clearfix"></div><br>
            <div class="row">
             <div class="col">
              <input name="nuovocorso" class="btn btn-warning" type="submit" value="Inserisci corso"/>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>

@endsection

@section('scripts')

  <script type="text/javascript">

    $(document).ready(function(){
      $('#long_id').keyup(function(){
          $(this).val($(this).val().toUpperCase());
          // console.log($(this).val());
      });

      $('.datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        dateFormat: 'dd/mm/yy',
        dayNamesShort: [ "Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa" ],
        dayNamesMin: [ "Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa" ],
        firstDay: 1

      });
    });

  </script>

@append

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

  .success-message{
      background: #d4edda;
      color: #155724;
      padding: 6px; 
      border-radius: 6px;
      position: relative;
      top:20px;
  }
  .alert-message{
      position: relative;
      top:20px;
  }
</style>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a role="button" href="{{route('courses.index')}}" class="btn btn-outline-secondary">Indietro</a>
            <div class="clearfix"></div><br>
            <div class="card">
                <div class="card-header">MODIFICA CORSO {{ strtoupper($course->long_id) }}</div>
                <div class="card-body">

                  <form action="{{ route('courses.update', $course->id) }}" method="post" id="formprova" name="a">
                      @csrf
                      @method('PUT')
                      <table>
                        <div class="row">
                          <div class="col">
                           <label> Descrizione Corso </label>
                           <input class="form-control" type="text" name="description" value="{{ $course->description }}"/>
                          </div>
                       </div>
                       <div class="row">
                        <div class="col">
                          <label for="start_date">Data inizio</label>
                          <input name="start_date" class="datepicker form-control" width="276" value=" {{ \Carbon\Carbon::parse($course->start_date)->format('d/m/Y')}}">
                        </div>
                        <div class="col">
                          <label for="end_date">Data fine</label>
                          <input name="end_date"class="datepicker datepicker_end form-control" width="276" value="{{ \Carbon\Carbon::parse($course->end_date)->format('d/m/Y')}}">
                        </div>
                      </div>
                       <div class="row">
                        <div class="col">
                           <label> Data corso </label>
                           <input class="form-control" type="text" name="date" value="{{ $course->date }}" />
                        </div>
                           <div class="col">
                            <label> Numero iscritti (indicativo)</label>
                           <div><select name="limit" class="form-control">
                             @php
                             for($i=5;$i<20;$i++){echo '<option value="'.$i.'">'.$i.'</option>';}
                            @endphp
                             <option value="20" selected="selected">20</option>
                             <option value="99">illimitato</option>
                           </select>
                          </div>
                        </div>
                     </div>
                     <div class="row">
                     <div class="col">
                       <label>CODICE CORSO: (max 5 caratteri)</label>
                       <div>
                        <input id="long_id" class="cod form-control" type="text" name="long_id" maxlength="10" value="{{ $course->long_id}}" />
                       </div>
                     </div> 
                   </div>
               </table>

               <br/>
               <div class="col-md-2">
                  <input name="modifica corso" class="btn btn-warning" type="submit" value="Salva modifiche"/>
              </div>
          </form>

      </div>
  </div>
</div>

  @if ($errors->any())
  <div class="col-md-8">
      <div class="alert alert-danger alert-message">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      </div>
  @endif

  @if ($message = Session::get('edited'))
  <div class="col-md-8">
      <div id="flash-message" class="success-message">
              <strong>{{ $message }}</strong>
      </div>
  </div>
  @endif

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
        firstDay: 1,
      });
    });

  </script>

@endsection

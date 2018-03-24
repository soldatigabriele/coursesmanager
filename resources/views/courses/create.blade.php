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

                  <form action="{{ route('course-store') }}" method="post" id="formprova" name="a">
                    {{ csrf_field() }}
                      <table>
                        <tr>
                           <td><label> Nome Corso </label></td>
                           <td><input class="form-control" type="text" name="description" value="{{ old('description') }}"/></td>
                       </tr>
                       <tr>
                           <td><label> Data corso </label></td>
                           <td><input class="form-control" type="text" name="date" value="{{ old('date') }}" /></td>
                       </tr>
                       <tr>
                           <td><label> Numero max iscritti (indicativo, pu&ograve essere superato)</label></td>
                           <td><select name="limit" class="form-control">
                             <?php
                             for($i=5;$i<20;$i++){echo '<option value="'.$i.'">'.$i.'</option>';}
                                 ?>
                             <option value="20" selected="selected">20</option>
                             <option value="99">illimitato</option>
                         </select></td>

                     </tr>
                     <tr> 
                       <td>CODICE CORSO: (max 5 caratteri)</td>
                       <td><input id="long_id" class="cod form-control" type="text" name="long_id" maxlength="10" value="{{ old('long_id')}}" /></td>
                   </tr>
               </table>

               <br/>
               <div class="col-md-2">
                  <input name="nuovocorso" class="btn btn-warning" type="submit" value="Inserisci corso"/>
              </div>
          </form>

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
    });

  </script>

@endsection

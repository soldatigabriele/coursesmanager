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
    .emails{
        border:1px solid #eee;
    }
    .tabella{
        padding-top: 20px;
    }
    .tabelle{
        padding-top: 20px;
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
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
                </div>
                @if ($message = Session::get('deleted'))
                <div class="col-md-6">
                    <div id="flash-message" class="success-message">
                            <strong>{{ $message }}</strong>
                    </div>
                </div>
                @endif
            </div>
            <div class="clearfix"></div><br>

            <div class="card">
                <div class="card-header">
                    <div class="row subtitle">
                        <div class="col-md-2">
                            Codice
                        </div>
                        <div class="col-md-4">
                            Descrizione
                        </div>
                        <div class="col-md-2">
                            Periodo
                        </div>
                        <div class="col-md-2">
                            Iscritti
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        @foreach($courses as $course)
                        <div class="row subtitle">
                            <div class="col-md-2">
                                {{ $course->long_id }}
                            </div>
                            <div class="col-md-4">
                                {{ $course->description }}
                            </div>
                            <div class="col-md-2">
                                {{ $course->date }}
                            </div>
                            <div class="col-md-2">
                                {{ $course->subs() }} / {{ $course->limit }}
                            </div>
                            <div class="col-md-1">
                                <button type="submit" data-toggle="collapse" data-target="#partecipants-{{ $course->id }}" aria-expanded="false" aria-controls="collapseExample" class="btn btn-outline-success">Mostra</button>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" data-course-long_id="{{ $course->long_id }}" data-course-date="{{ $course->date }}" data-course-description="{{ $course->description }}" data-course-id="{{$course->id}}" class="btn btn-outline-danger delete-button">Elimina</button>
                            </div>
                        </div>
                        <div class="col-md-12 tabella">
                            <div class="collapse table" id="partecipants-{{ $course->id }}">
                                <table id="dir_table" class="table table-bordered table-striped dataTable tabella" aria-describedby="example1_info">
                                    <tr class="subtitle tabelle">
                                        <td>
                                            #
                                        </td>
                                        @foreach($course->headers() as $key)
                                        <td>
                                            {{ ucfirst($key) }}
                                        </td>
                                        @endforeach
                                    </tr>
                                    @php $i = 1; @endphp
                                    @foreach($course->partecipants as $p)
                                    <tr>
                                        <td>
                                            {{$i}} 
                                            @php $i++; @endphp
                                        </td>
                                        <td>
                                            {{ $p->name }}
                                        </td>
                                        <td>
                                            {{ $p->surname }}
                                        </td>
                                        <td>
                                            {{ $p->email }}
                                        </td>
                                        <td>
                                            {{ $p->phone }}
                                        </td>
                                        <td>
                                            {{ $p->region['name'] }}
                                        </td>
                                        @foreach($p->getData() as $key => $value)

                                        <td>
                                            {{ ($value) }}
                                        </td>
                                        @endforeach                                        
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="col-md-12 emails">  
                            @foreach($course->getDistinctEmails('email') as $p)
                            
                                {{ $p->email }},
                        
                            @endforeach
                            </div>
                        </div>
                        <br>
                        <hr>       
                        @endforeach

                    </div>
                </div>
                {!! $courses->render() !!}
            </div>
        </div>
@endsection




<!-- MODAL -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Elimina Corso</h4>
    </div>
    <div class="modal-body">
        <p>Vuoi eliminare questo corso?</p>
        <div style="padding:10px;border:solid 1px #efefef">
            
        </style>
            
        <p id="course-long_id"></p>
        <p id="course-description"></p>
        <p id="course-date"></p>
        </div>
    </div>
    <div class="modal-footer">
        <form action="" id="form-delete" method="post"> 
            <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
            <button type="submit" class="btn btn-danger">Elimina</button>
             {{ method_field('DELETE') }} 
            {{ csrf_field() }}
        </form>
    </div>

</div>
</div>
</div>

@section('scripts')
  <script>

$(document).ready(function(){
    $( "#flash-message" ).delay(4000).fadeOut( "slow");
    
    let originalUrl = $('#form-delete').attr('action');
    $('.delete-button').on('click', function (event) {
        var url = 'courses/'+ $(this).attr('data-course-id');
        $('#form-delete').attr('action', url);
        $('#course-long_id').html($(this).attr('data-course-long_id'));
        $('#course-description').html($(this).attr('data-course-description'));
        $('#course-date').html($(this).attr('data-course-date'));
        $('#myModal').modal('show');
        event.preventDefault();
    });

});

  </script>


@endsection


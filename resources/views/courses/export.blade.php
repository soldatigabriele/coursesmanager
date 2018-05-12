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
    .table-borders{
        padding-top: 20px;
    }
</style>
@endsection

@section('content')
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <a role="button" href="{{route('courses.index')}}" class="btn btn-outline-secondary">Indietro</a>
                </div>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row subtitle" id="course-{{ $course->id }}">
                            <div class="col-md-2">
                                {{ $course->long_id }}
                            </div>
                            <div class="col-md-4">
                                {{ $course->description }}
                            </div>
                            <div class="col-md-2">
                                {{ $course->date }}
                            </div>
                            <div class="col-md-1">
                                {{ $course->subs() }} / {{ $course->limit }}
                            </div>
                        </div>
                        <div class="col-md-12 tabella">
                            <div class="table" id="partecipants-{{$course->id}}">
                                <table id="dir_table" class="table table-bordered dataTable tabella" aria-describedby="example1_info">
                                    <tr class="subtitle tabelle">
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            Nome
                                        </td>
                                        <td>
                                            Cognome
                                        </td>
                                        <td>
                                            Email
                                        </td>
                                        <td>
                                            Telefono
                                        </td>
                                        <td>
                                            Provenienza
                                        </td>
                                        <td>
                                            Mezzo
                                        </td>
                                    </tr>
                                    @php $i = 1; @endphp
                                    @foreach($course->partecipants as $p)
                                    <tr class="" id="partecipant-{{ $p->id }}">
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
                                            @if($p->shares == 'Si') {{ $p->email }} @else {{'NON CONDIVIDE'}} @endif
                                        </td>
                                        <td>
                                            @if($p->shares == 'Si') {{ $p->phone }} @else {{'NON CONDIVIDE'}} @endif
                                        </td>
                                        <td>
                                            {{ $p->city }}
                                        </td>
                                        <td>
                                            {{ $p->transport }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
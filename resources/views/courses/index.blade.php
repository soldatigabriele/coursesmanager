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
</style>
@endsection

@section('content')


        <div class="col-md-12">
            <a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
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
                            Limite Iscritti
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12 tabella">
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
                            <div class="col-md-2">
                                <button type="submit" data-toggle="collapse" data-target="#partecipants-{{ $course->id }}" aria-expanded="false" aria-controls="collapseExample" class="btn btn-outline-success">Mostra</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="collapse table" id="partecipants-{{ $course->id }}">
                                <table id="dir_table" class="table table-bordered table-striped dataTable tabella" aria-describedby="example1_info">

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
                                            Regione
                                        </td>
                                        <td>
                                            Città
                                        </td>
                                        <td>
                                            Trasporto
                                        </td>
                                        <td>
                                            Cibo
                                        </td>
                                        <td>
                                            Saputo da
                                        </td>
                                        <td>
                                            Condivide
                                        </td>
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
                                            {{ $p->getData()->region }}
                                        </td>
                                        <td>
                                            {{ $p->getData()->city }}
                                        </td>
                                        <td>
                                            {{ $p->getData()->transport }}
                                        </td>
                                        <td>
                                            {{ $p->getData()->food }}
                                        </td>
                                        <td>
                                            {{ $p->getData()->source }}
                                        </td>
                                        <td>
                                            {{ $p->getData()->shares }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            <span class="subtitle">Lista Mail {{ $course->long_id }}:</span>
                            <div class="col-md-12 emails">  
                            @foreach($course->partecipants as $p)
                            
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

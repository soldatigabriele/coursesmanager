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
                            <div class="col-md-2">
                                <button type="submit" data-toggle="collapse" data-target="#partecipants-{{ $course->id }}" aria-expanded="false" aria-controls="collapseExample" class="btn btn-outline-success">Mostra</button>
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
                                        @foreach($p->getData() as $key => $value)

                                        <td>
                                            {{ ($value) }}
                                        </td>
                                        @endforeach                                        
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

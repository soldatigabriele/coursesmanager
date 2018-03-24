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
                        <div class="col-md-12">
                            <div class="collapse" id="partecipants-{{ $course->id }}">
                            <div class="row subtitle">
                                <div class="col-md-1">
                                    #
                                </div>
                                <div class="col-md-1">
                                    Nome
                                </div>
                                <div class="col-md-1">
                                    Cognome
                                </div>
                                <div class="col-md-2">
                                    Email
                                </div>
                                <div class="col-md-1">
                                    Telefono
                                </div>
                                <div class="col-md-1">
                                    Regione
                                </div>
                                <div class="col-md-1">
                                    Città
                                </div>
                                <div class="col-md-1">
                                    Trasporto
                                </div>
                                <div class="col-md-1">
                                    Vegetariano
                                </div>
                                <div class="col-md-1">
                                    Source
                                </div>
                                <div class="col-md-1">
                                    Shares
                                </div>
                            </div>
                            @php $i = 1; @endphp
                            @foreach($course->partecipants as $p)
                                <div class="row righe">
                                    <div class="col-md-1">
                                        {{$i}} 
                                        @php $i++; @endphp
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->name }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->surname }}
                                    </div>
                                    <div class="col-md-2">
                                        {{ $p->email }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->phone }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->getData()->region }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->getData()->city }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->getData()->transport }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->getData()->food }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->getData()->source }}
                                    </div>
                                    <div class="col-md-1">
                                        {{ $p->getData()->shares }}
                                    </div>
                                </div>

                            @endforeach
                            </div>
                        </div>
                        <hr>
                        @endforeach
                    </div>
                </div>
                {!! $courses->render() !!}
            </div>
        </div>


@endsection

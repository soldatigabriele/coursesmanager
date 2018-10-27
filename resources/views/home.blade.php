@extends('layouts.app')

@section('style')
<style type="text/css">
    .link{
        border: 1px solid #efefef;
        padding: 3px 10px 3px 20px;
        border-radius: 7px;
        width: 100%;
    }
    .vai-scheda{
        padding: 0px 10px 0px 15px;
    }
    .main-buttons>a{
        min-width: 160px;
        float:right;
    }
    .schede-buttons>a{
        min-width: 180px;
    }
    .table-buttons>a{
        width: 100%;
        margin-left: 0%;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header"><h4>Pannello di controllo</h4></div>
                <div class="card-body">
                    <div class="container">
                        <div class="col-md-12">
                            
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <h5>Crea un nuovo corso</h5>
                            </div>
                            <div class="col-md-4 main-buttons">
                                <a role="button" href="courses/create" class="btn btn-outline-warning">Crea Corso</a>
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <h5>Controlla le Tabelle esistenti</h5>
                            </div>
                            <div class="col-md-4 main-buttons">
                                <a role="button" href="courses" class="btn btn-outline-success">Tabelle</a>
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <h5>Lista Mail</h5>
                            </div>
                            <div class="col-md-4 main-buttons">
                                <a role="button" href="{{ route('partecipant.index') }}" class="btn btn-outline-info">Lista Mail</a>
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <h5>Newsletter</h5>
                            </div>
                            <div class="col-md-4 main-buttons">
                                <a role="button" href="{{ route('newsletter.index') }}" class="btn btn-outline-dark">Newsletter</a>
                            </div>
                        </div>
                        </div>

                    <br>
                    <hr>
                    <h4>Schede Iscrizione</h4>
                    <div class="row justify-content-between">
                        <div class="col-3 schede-buttons" >
                            <a role="button" href="{{ route('scheda-1') }}" class="btn btn-outline-primary">Vai alla scheda 1</a>
                        </div>
                        <div class="col-6 link">
                            <span id="copyTarget1">{{ route('scheda-1') }}</span> 
                        </div>
                        <div class="col-2">
                            <button id="copyButton" onclick="copyToClipboard('#copyTarget1'), changeClass.call(this)" class="btn btn-xs btn-outline-secondary copyButton">Copia</button>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-between">
                        <div class="col-3 schede-buttons" >
                            <a role="button" href="{{ route('scheda-2') }}" class="btn btn-outline-primary">Vai alla scheda 2</a>
                        </div>
                        <div class="col-6 link">
                            <span id="copyTarget2">{{ route('scheda-2') }}</span> 
                        </div>
                        <div class="col-2">
                            <button id="copyButton" onclick="copyToClipboard('#copyTarget2'), changeClass.call(this)" class="btn btn-xs btn-outline-secondary copyButton">Copia</button>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-between">
                        <div class="col-3 schede-buttons" >
                            <a role="button" href="{{ route('scheda-3') }}" class="btn btn-outline-primary">Vai alla scheda 3</a>
                        </div>
                        <div class="col-6 link">
                            <span id="copyTarget3">{{ route('scheda-3') }}</span> 
                        </div>
                        <div class="col-2">
                            <button id="copyButton" onclick="copyToClipboard('#copyTarget3'), changeClass.call(this)" class="btn btn-xs btn-outline-secondary copyButton">Copia</button>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-between">
                        <div class="col-3 schede-buttons">
                            <a role="button" href="{{ route('newsletter.create') }}" class="btn btn-outline-primary">Iscrizione Newsletter</a>
                        </div>
                        <div class="col-6 link">
                            <span id="copyTarget4">{{ route('newsletter.create') }}</span> 
                        </div>
                        <div class="col-2">
                            <button id="copyButton" onclick="copyToClipboard('#copyTarget4'), changeClass.call(this)" class="btn btn-xs btn-outline-secondary copyButton">Copia</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="clearfix"></div><br>

        <div class="card">
            <div class="card-header"><h4>Ultimi iscritti</h4></div>
                <div class="card-body">
                    <div class="container">
                        <div class="md-12">
                            <table id="dir_table" class="table table-bordered table-striped dataTable tabella" aria-describedby="example1_info">
                                <tr>
                                    <th>
                                        Nome
                                    </th>
                                    <th>
                                        Email
                                    </th>
                                    <th>
                                        Telefono
                                    </th>
                                    <th>
                                        Codice Corso
                                    </th>
                                    <th>
                                        Iscritto
                                    </th>
                                    <th>
                                        Dettagli
                                    </th>
                                </tr>
                            @foreach($partecipants as $p)
                                <tr>
                                    <td>
                                        {{ $p->surname }} {{ $p->name }}
                                    </td>
                                    <td>
                                        {{ $p->email }}
                                    </td>
                                    <td>
                                        {{ $p->phone }}
                                    </td>
                                    <td class="table-buttons">
                                        <a role="button" href="{{ route('courses.index', ['course_id' =>$p->courses()->latest()->first()->id, 'partecipant_id' => $p->id] ) }}" class="btn btn-outline-dark btn-sm">{{ $p->courses()->latest()->first()->long_id }}</a>
                                    </td>
                                    <td>
                                        {{ $p->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <a role="button" href="{{ route('partecipant.show', $p->slug) }}" class="btn btn-outline-dark btn-sm">Dettagli</a>
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
</div>
@endsection

@section('scripts')
<script>
    function copyToClipboard(element) {
        $('.copyButton').attr('class', 'btn btn-outline-secondary copyButton');
        $('.copyButton').html('Copia');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
    function changeClass(){
        $(this).attr('class', 'btn btn-outline-success copyButton');
        $(this).html('Copiato');
    }
</script>
@endsection

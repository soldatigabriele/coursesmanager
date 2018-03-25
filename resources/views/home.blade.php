@extends('layouts.app')

@section('style')
<style type="text/css">
    .link{
        border: 1px solid #efefef;
        padding: 3px 10px 3px 20px;
        border-radius: 7px;
    }
    .vai-scheda{
        padding: 0px 10px 0px 15px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header"><h4>Pannello di controllo</h4></div>
                    <div class="card-body">
                        <div class="container">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Crea un nuovo corso</h5>
                                </div>
                                <div class="col-md-4 offset-md-1">
                                    <a role="button" href="courses/create" class="btn btn-outline-danger">Crea un nuovo Corso</a>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Controlla le Tabelle esistenti</h5>
                                </div>
                                <div class="col-md-4 offset-md-1">
                                    <a role="button" href="courses" class="btn btn-outline-success">Tabelle</a>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Lista Mail</h5>
                                </div>
                                <div class="col-md-4 offset-md-1">
                                    <a role="button" href="{{ route('partecipant-index') }}" class="btn btn-outline-warning">Lista Mail</a>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Newsletter</h5>
                                </div>
                                <div class="col-md-4 offset-md-1">
                                    <a role="button" href="{{ route('newsletter-index') }}" class="btn btn-outline-warning">Newsletter</a>
                                </div>
                            </div>
                        <br>
                        <hr>
                        <h4>Schede Iscrizione</h4>
                        <div class="row">
                            <div class="md-2 vai-scheda" >
                                <a role="button" href="{{ route('scheda-1') }}" class="btn btn-outline-primary">Vai alla scheda 1</a>
                            </div>
                            <div class="link md-6">
                                <span id="copyTarget1">{{ route('scheda-1') }}</span> 
                            </div>
                            <div class="md-2 offset-md-1">
                                <button id="copyButton" onclick="copyToClipboard('#copyTarget1'), changeClass.call(this)" class="btn btn-xs btn-outline-secondary copyButton">Copia Link</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="md-2 vai-scheda" >
                                <a role="button" href="{{ route('scheda-2') }}" class="btn btn-outline-primary">Vai alla scheda 2</a>
                            </div>
                            <div class="link md-6">
                                <span id="copyTarget2">{{ route('scheda-2') }}</span> 
                            </div>
                            <div class="md-2 offset-md-1">
                                <button id="copyButton" onclick="copyToClipboard('#copyTarget2'), changeClass.call(this)" class="btn btn-xs btn-outline-secondary copyButton">Copia Link</button>
                            </div>
                        </div>
                        <br>
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
        $('.copyButton').html('Copia Link');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
    function changeClass(){
        $(this).attr('class', 'btn btn-outline-primary copyButton');
        $(this).html('Link copiato');
    }
</script>
@endsection

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
    .titles{
        font-weight: 600;
    }

</style>
@endsection

@section('content')
<div class="col-md-12">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
            <div class="clearfix"></div><br>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header"><h4>Lista di tutti i corsisti </h4></div>
                    <div class="card-body">

                            <table class="table table-bordered table-striped" >
                            <tr class="titles">
                                <td>
                                    #
                                </td>
                                <td >
                                    Cognome e Nome
                                </td>
                                <td >
                                    Regione
                                </td>
                                <td >
                                    Email
                                </td>
                                <td >
                                    Data corso
                                </td>
                                <td >
                                    Descrizione corso
                                </td>
                                <td >
                                    Codice Corso
                                </td>
                            </tr>
                            @foreach($partecipants as $n)
                                <tr>
                                    <td >
                                        {{$n->id}}
                                    </td>
                                    <td >
                                        {{$n->surname}} {{$n->name}}
                                    </td>
                                    <td >
                                        {{ $regions[$n->region_id-1]['name'] }}
                                    </td>
                                    <td >
                                        {{$n->email}}
                                    </td>
                                    <td >
                                        {{ $n->courses->first()->date }}
                                    </td>
                                    <td >
                                        {{ $n->courses->first()->description }}
                                    </td>
                                    <td >
                                        {{ $n->courses->first()->long_id }}
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        {{ $partecipants->links() }}


                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header"><h4>Lista di tutte le Email dei corsisti</h4></div>
                    <div class="card-body">
                        <div class="container">
                            <div class="col">
                                @foreach($emails as $e)
                                    {{$e->email}},
                                @endforeach
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
        $('.copyButton').html('Copia Link');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
    function changeClass(){
        $(tdis).attr('class', 'btn btn-outline-primary copyButton');
        $(tdis).html('Link copiato');
    }
</script>
@endsection

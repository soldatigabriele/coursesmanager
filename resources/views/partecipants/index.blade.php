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
    .ricerca{
        position:relative;
        top:30px;
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
                        <div class="col-md-12">
                            <form action="{{ route('partecipant-index') }}" method="get" accept-charset="utf-8">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                        <label>Regione:</label>
                                        <select class="form-control" name="region_id">
                                          <option value="empty"> - </option>
                                          @foreach($regions as $region)
                                            <option value="{{ $region->id }}" @if($region_id == $region->id)selected @endif>{{ $region->name}}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>
                                    <!-- <div class="col">
                                        <label>Nome:</label>
                                        <div class="form-group">
                                        <input type="text" name="name" id="name" class="form-control input-lg" placeholder="" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Cognome:</label>
                                            <input type="text" name="surname" id="surname" class="form-control input-lg" placeholder="" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input type="text" name="email" id="email" class="form-control input-lg" placeholder="" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Telefono:</label>
                                            <input type="text" name="mobile" id="mobile" class="form-control input-lg" placeholder="" value="">
                                        </div>
                                    </div> -->
                                    <div class="col">
                                        {{ csrf_field() }}
                                        <input class="btn btn-md btn-success ricerca" name="find" type="submit" value="Ricerca">
                                    </div>
                                </div>
                            </form>
                        </div>
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
                                    Tipo
                                </td>
                            </tr>
                            @foreach($partecipants as $n)
                                <tr>
                                    <td>
                                        {{$n->id}}
                                    </td>
                                    <td>
                                        {{$n->surname}} {{$n->name}}
                                    </td>
                                    <td>
                                        {{ $regions[$n->region_id-1]['name'] }}
                                    </td>
                                    <td>
                                        {{$n->email}}
                                    </td>
                                    <td>
                                        @if($n->slug)
                                            <a href="{{ route('partecipant-show', $n->slug) }}" role="button" class="btn btn-sm btn-outline-secondary">Dettagli</a>
                                        @else 
                                            Newsletter
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        {{ $partecipants->links() }}


                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header"><h4>Lista di tutte le Email</h4></div>
                    <div class="card-body">
                        <div class="container">
                            <div class="col">
                                @foreach($emails as $e)
                                    {{$e}},
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

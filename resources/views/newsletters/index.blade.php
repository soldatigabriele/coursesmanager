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
        <div class="col-md-12">
            <a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
            <div class="clearfix"></div><br>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header"><h4>Lista Newsletter - Email Totali (Attivi): {{ count($emails) }}/ tot</h4></div>
                    <div class="card-body">
                        <div class="container">
                            Lista:
                            @foreach($newsletters->sortByDesc('created_at') as $n)
                            <div class="row">
                                <div class="col">
                                    {{$n->id}}
                                </div>
                                <div class="col">
                                    {{$n->surname}} {{$n->name}}
                                </div>
                                <div class="col">
                                    {{ $regions[$n->region_id-1]['name'] }}
                                </div>
                                <div class="col">
                                    {{$n->email}}
                                </div>
                            </div>
                            <hr>
                            @endforeach
                        </div>
                    {!! $newsletters->render() !!}
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header"><h4>Lista Email Newsletter</h4></div>
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
        $(this).attr('class', 'btn btn-outline-primary copyButton');
        $(this).html('Link copiato');
    }
</script>
@endsection

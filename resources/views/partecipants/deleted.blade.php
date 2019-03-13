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
                        <table class="table table-bordered table-striped" >
                            <tr class="titles">
                                <td>
                                    #
                                </td>
                                <td >
                                    Cognome e Nome
                                </td>
                                <td >
                                    Email
                                </td>
                                <td >
                                    Cancellato il
                                </td>
                                <td>
                                    Manage
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
                                        {{$n->email}}
                                    </td>
                                    <td>
                                        {{$n->deleted_at->format('d/m/Y - H:i') }}
                                    </td>
                                    <td>
                                        @if($n->slug)
                                            <a href="{{ route('partecipant.show', $n->slug) }}" role="button" class="btn btn-sm btn-outline-secondary">Apri</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

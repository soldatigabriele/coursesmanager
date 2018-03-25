@extends('layouts.app')

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
                <div class="card-header">Control Panel</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                        <a role="button" href="courses/create" class="btn btn-outline-warning">Crea Corso</a>
                        </div>
                        <div class="col">
                        <a role="button" href="courses" class="btn btn-outline-success">Tabelle</a>
                        </div>
                    </div>
                    <br>
                    <div class="col">
                    <a role="button" href="{{ route('scheda-1') }}" class="btn btn-outline-success">Scheda 1</a>
                    {{ route('scheda-1') }}
                    </div>
                    <br>
                    <div class="col">
                    <a role="button" href="{{ route('scheda-2') }}" class="btn btn-outline-success">Scheda 2</a>
                    {{ route('scheda-2') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

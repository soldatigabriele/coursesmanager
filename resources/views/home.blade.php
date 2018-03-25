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
                    <a role="button" href="courses" class="btn btn-outline-success">Tabelle</a>
                    <a role="button" href="courses/create" class="btn btn-outline-warning">Crea Corso</a>
                    <a role="button" href="{{ route('partecipant-create') }}" class="btn btn-outline-success">Scheda</a>
            <!--         <a role="button" href="courses" class="btn btn-outline-success">Tabelle</a>
                    <a role="button" href="courses" class="btn btn-outline-success">Tabelle</a>
                    <a role="button" href="courses" class="btn btn-outline-success">Tabelle</a> -->
        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

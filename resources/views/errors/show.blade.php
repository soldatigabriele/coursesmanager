@extends('layouts.app')

@section('content')
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
                </div>
            </div>
            <div class="clearfix"></div><br>

            @php
                $errorKeys = [];
            @endphp
            <div class="card">
                <div class="card-header">
                    <div class="row subtitle">
                        <div class="col-md-2">
                            Error log #{{$error->id}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ID:</td>
                                    <td>{{ $error->id }}</td>
                                </tr>
                                <tr>
                                    <td>Descrizione:</td>
                                    <td>{{ $error->description }}</td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td>{{ $error->status }}</td>
                                </tr>
                                <tr>
                                    <td>Meta data:</td>
                                    <td>{{ $error->meta }}</td>
                                </tr>
                                <tr>
                                    <td>Errori:</td>
                                    <td>
                                    @isset($error->value['errors'])
                                        @foreach($error->value['errors'] as $key => $value)
                                            @foreach($value as $err)
                                                @php
                                                    $errorKeys[] = $key;
                                                @endphp

                                                <span style="font-weight:600">{{ $key }}:</span> {{$err }} <br>
                                            @endforeach
                                        @endforeach
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Altri input:</td>
                                    <td>
                                    @php
                                        $values = $error->value;
                                        unset($values['errors']);
                                        unset($values['_token']);
                                        unset($values['subscribe']);
                                        unset($values['g-recaptcha-response']);
                                    @endphp
                                    @foreach($values as $key => $value)
                                        <span style="@if(in_array($key, $errorKeys)) color:red; @endif">
                                            <span style="font-weight:600;">{{ $key }}:</span> {{ $value }} <br>
                                        </span>
                                    @endforeach
                                    </td>
                                </tr>
                                 <tr>
                                    <td>Data:</td>
                                    <td>{{ $error->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection



@extends('layouts.app')

@section('content')
 <div class="container">
    <div class="col-md-1">
        <a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
    </div>

    <div class="col-md-12">
        <div class="clearfix"></div><br>
        <div class="card">
            <div class="card-header"><h4>Errori Recenti</h4></div>
                <div class="card-body">
                    <div class="container">
                        <div class="md-12">
                            <table id="dir_table" class="table table-bordered table-striped dataTable tabella" aria-describedby="example1_info">
                                <tr>
                                    <th>
                                        Id
                                    </th>
                                    <th>
                                        Description
                                    </th>
                                    <th width="300">
                                        Value
                                    </th>
                                    <th>
                                        Data
                                    </th>
                                    <th>
                                        Dettagli
                                    </th>
                                </tr>
                            @foreach($errors as $error)
                                <tr>
                                    <td>
                                        {{ $error->id }}
                                    </td>
                                    <td width="300">
                                        {{ $error->description }}
                                    </td>
                                    <td>
                                        @isset($error->value['errors'])
                                            @foreach($error->value['errors'] as $key => $value)
                                            <ul>
                                                @foreach($value as $err)
                                                    <li>{{ $err }}</li>
                                                @endforeach
                                            </ul>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        {{ $error->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <a role="button" href="{{ route('errors.show', $error->id) }}" class="btn btn-outline-dark btn-sm">Dettagli</a>
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
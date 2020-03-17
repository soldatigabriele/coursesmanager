@extends('layouts.app')

@section('style')
<style>
.content{
    width:96%;
    margin:auto;
}
</style>
@endsection

@section('content')
<div class="content">
    <div class="row justify-content-center">
        @foreach($courses as $course)

        <div class="card">
        <div class="card-header">
            <h3>{{ $course->long_id }}</h3>
        </div>
        <div class="card-body">
            <div>
                {{ route('questions-create', [ 'course_id' => $course->id, 'qn' => 3, ]) }}
            </div>
            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>Domande</th>
                    <th>Feedback</th>
                </thead>
                @foreach($course->questions()->get() as $question)
                <tr>
                    <td> {{ $question->id }} </td>    
                    <td> {{ $question->name}} {{ $question->surname }} </td>    
                    <td> {{ $question->created_at->format('d/m/Y H:i')}} </td>    
                    <td> 
                        <ul>
                            @foreach($question->questions as $q)
                            <li>{{ $q }} </li>
                            @endforeach
                        </ul>
                    </td>    
                    <td> 
                        <ul>
                            @foreach($question->feedback as $q)
                            <li>{{ $q }} </li>
                            @endforeach
                        </ul>
                    </td>    
                </tr>
                @endforeach
            </table>
        </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


@extends('layouts.app')

@section('style')
<style>
.content{
    width:96%;
    margin:auto;
}
.card {
    margin-bottom: 20px;
}
.form {
    float: left;
    padding-right: 20px;
}
.form span{
    padding-left: 20px;
}
#copyButton {
    position:relative;
    bottom: 9px; 
}
.link span {
    background: #F4F4F4;
    border: 1px solid gray;
    padding: 4px;
    border-radius: 3px;
}
</style>
@endsection

@section('content')
<div class="content">
    @foreach($courses as $course)
        <div class="card">
            <div class="card-header">
                <h3>{{ $course->long_id }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form col-4">
                        <span>Numero domande: <input data-id="{{$course->id}}" type="text" class="questions" size="2" maxlength="2" value="3"></span>
                        <span><label for="feedback-{{$course->id}}">Include Feedback:</label> <input id="feedback-{{$course->id}}" data-id="{{$course->id}}" type="checkbox" checked="true"></span>
                    </div>
                     <div class="col-6 link">
                        <span class="link-{{$course->id}}">
                            {{ route('questions-create', [ 'course_id' => $course->id, 'qn' => 3, 'feed' => 1]) }}
                        </span> 
                    </div>
                    <div class="col-2">
                        <button id="copyButton" onclick="copyToClipboard('.link-{{$course->id}}'), changeClass.call(this)" class="btn btn-xs btn-outline-secondary copyButton">Copia Link</button>
                    </div>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $(".form input:checkbox").change(function() {
            // Replace the feed 1 or 0 with the checkbox
            let link = $(".link-" + $(this).attr('data-id')).text()
            let text = link.replace(/feed=[0-1]/, 'feed=' + ($(this).prop('checked') ? 1 : 0));
            $(".link-" + $(this).attr('data-id')).text(text)
        })
        $(".form .questions").on("keyup", function() {
            // Replace the number of questions
            let link = $(".link-" + $(this).attr('data-id')).text()
            let text = link.replace(/qn=(?:[0-9]{1,2})?/, 'qn=' + $(this).val());
            $(".link-" + $(this).attr('data-id')).text(text)
        })
    })

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
        $(this).attr('class', 'btn btn-outline-success copyButton');
        $(this).html('Copiato');
    }
</script>
@endsection

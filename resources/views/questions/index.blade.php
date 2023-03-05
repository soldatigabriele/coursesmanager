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
.options{
    background: white;
    border: 1px solid #F4F4F4;
    margin-bottom: 10px;
    padding: 10px;
}
</style>
@endsection

@section('content')
<div class="content">

    <div class="options">
        <div class="row">
            <div class="col-auto">
                <label for="questions-include-future">Includi corsi futuri &nbsp;</label>
                <input id="questions-include-future" type="checkbox">
            </div>
            <div class="col-auto">
                <label for="questions-include-days">Includi corsi finiti da non più di: &nbsp;</label>
                <select id="questions-include-days">
                    <option value="" selected disabled>-</option>
                    <option value="7">7 giorni</option>
                    <option value="30">30 giorni</option>
                    <option value="60">60 giorni</option>
                    <option value="90">3 mesi</option>
                    <option value="180">6 mesi</option>
                    <option value="365">1 anno</option>
                </select>
            </div>
        </div>
    </div>

    @foreach($courses as $course)
        <div class="card" id="course-{{$course->id}}">
            <div class="card-header">
               <div class="row">
                    <div class="col">
                        <h3>{{ $course->long_id }}</h3>
                    </div>
                    <div class="col-auto">
                        <h4 class="subtitle">{{$course->start_date->format('d/m/Y')}} - {{$course->end_date->format('d/m/Y')}}</h4>
                    </div>
               </div>
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
    // Get references to the checkbox and select element
    const futureCheckbox = document.getElementById("questions-include-future");
    const endSelect = document.getElementById("questions-include-days");

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

        // Check if we have "future" parameter in the url
        let searchParams = new URLSearchParams(window.location.search)
        if(searchParams.has('future') && searchParams.get('future') === '1'){
            // Set the future checkbox to true
            $('#questions-include-future').prop( "checked", true );
        }
        
        if(searchParams.has('end')){
            // Set the end option to the value
            $('#questions-include-days').val(searchParams.get('end'));
        } else {
            $('#questions-include-days').val(90);
        }

        // Listen for changes on the checkbox and select element
        futureCheckbox.addEventListener("change", updateUrl);
        endSelect.addEventListener("change", updateUrl);
    })

    // Function to update the URL
    function updateUrl() {
        // Get the current URL
        let url = new URL(window.location.href);

        // Update the URL with the checkbox state and selected value, if any
        if (futureCheckbox.checked) {
            url.searchParams.set("future", "1");
        } else {
            url.searchParams.delete("future");
        }
        
        if (endSelect.value !== "") {
            url.searchParams.set("end", endSelect.value);
        } else {
            url.searchParams.delete("end");
        }

        // Redirect to the updated URL
        window.location.href = url.toString();
    }

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

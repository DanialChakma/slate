@extends('layouts.app')

@php
$title = "Edit Survey";
@endphp

@section('title',$title)

@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_survey"];
    </script>
@endsection
@section('content')
   <h1>{{ $title }}</h1>
    <form class="form-horizontal" method="POST" role="form" action = "{{route( 'surveys.update',[ 'id'=>$survey->id ] )}}" >
        {{ csrf_field() }}
        <div class="input-area">
            <label for="select">Department <span class="required">*</span></label>
            <select name="department_id" id="department_id" >
                @foreach(\App\Department::all() as $department)
                    @if( $survey->department_id == $department->id )
                        <option selected value="{{$department->id}}">{{$department->name}}</option>
                    @else
                        <option value="{{$department->id}}">{{$department->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="input-area">
            <label for="select">Project <span class="required">*</span></label>
            <select  name="project_id" id="project_id" required>
                <option value="">--Select Project--</option>
                @foreach($survey->project->department->projects as $project)
                    @if( $project->id == $survey->project_id )
                        <option selected value="{{$project->id}}">{{$project->name}}</option>
                    @else
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div id="survey_questions" class="input-area">
            @foreach($survey->questions as $index=>$selectedQuestion)
                <div class="question input-area">
                    <div class="fr"><a href="javascript:void(0)" class="removebtn"><span class="remove">remove</span> </a> </div>
                    <div class="input-area">

                        <label for="question_counter">Question #{{$index+1}}</label>
                        <select class="selected_question" name="selected_question[]" required>
                            <option value="">--Select Question--</option>
                            @foreach($questions as $question)
                                @if( $question->id == $selectedQuestion->pivot->question_id )
                                    <option selected value="{{$question->id}}" >{{$question->body}}</option>
                                @else
                                    <option value="{{$question->id}}" >{{$question->body}}</option>
                                @endif
                            @endforeach
                        </select>


                        <div class="answer-list seven columns fr">
                            <ul>
                                @foreach($questions->where('id',$selectedQuestion->pivot->question_id)->first()->answerOptions as $indx=>$option)
                                    <li>{{$option->key}}: {{$option->body}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="input-area" style="margin-top: 14px;display: none">
            @foreach($questions as $question)
                @foreach($question->answerOptions as $option)
                    <input type="hidden" name="{{$question->id}}" value="{{$option->key."=".$option->body}}" />
                @endforeach
            @endforeach

            <select  name="select_question_id" id="select_question_id">

                <option value="">--Select Question--</option>
                @foreach($questions as $question)
                    <option value="{{$question->id}}">{{$question->body}}</option>
                @endforeach

            </select>
            <div class="answer-list seven columns fr"></div>
        </div>

        <div class="section">
<a type="button"  href="javacript:void(0);" class="fr add_question add-btn">+ Add Another Question</a>                 
        </div>
        <div class="button-area">
            <a href="{{ route('surveys') }}" class="big-btn">Back</a>
            <button type="submit" class="big-btn">Update</button>
        </div>
    </form>
@endsection
@section('FooterAdditionalCodes')
    <script type="text/javascript" src="{{asset('js/JconfirmFunctions.js')}}"></script>
    <script type="text/javascript">

        function make_question_exclusive(){
            var SelectedQuestions = [];
            $('#survey_questions').find('select')
                    .each(function(index, elem) {
                        var selVal = $(elem).find('option:selected').val();
                        if (selVal != "") SelectedQuestions.push(selVal);
                    })
                    .end()
                    .each(function(index, elem) {
                        $(elem).find('option[value!=""]')
                                .filter(function(index, option) {
                                    return SelectedQuestions.indexOf(option.value) !== -1;
                                })
                                .hide()
                                .end()
                                .filter(function(index, option) {
                                    return SelectedQuestions.indexOf(option.value) == -1;
                                }).show()
                                .end()

                    })
                    .end()

            $('select#select_question_id')
                    .each(function(index, elem) {
                        $(elem).find('option[value!=""]')
                                .filter(function(index, option) {
                                    return SelectedQuestions.indexOf(option.value) !== -1;
                                })
                                .hide()
                                .end()
                                .filter(function(index, option) {
                                    return SelectedQuestions.indexOf(option.value) == -1;
                                }).show()
                                .end()

                    })
                    .end()
        }


        $(document).ready(function(){

            var question_count = {{ $survey->questions->count()+1 }}

            make_question_exclusive();

            $(document).on('click','.add_question',function(event){
                event.preventDefault();

                var SelectElement = $('<select class="selected_question" name="selected_question[]" required />').append($('#select_question_id').html());
                var outerSelectedHtml = $('<div/>').append(SelectElement).html();



                var question_div = '<div class="question input-area">'+
                        '<div class="fr"><a href="javascript:void(0)" class="removebtn"><span class="remove">remove</span> </a> </div>'+
                        '<div class="input-area">'+
                        '<label for="question_counter">Question #'+question_count+'</label>'+
                        outerSelectedHtml+
                        '<div class="answer-list seven columns fr">'+

                        '</div>'+
                        '</div>'+
                        '</div>';

                $("#survey_questions").append(question_div);


                question_count++;

                $('#select_question_id').closest('.input-area').find('.answer-list').html('');
                var SelectedQuestions = [];
                $('#survey_questions').find('select')
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedQuestions.push(selVal);
                        })
                        .end()
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedQuestions.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedQuestions.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end()

            });


            $(document).on('change','#survey_questions select.selected_question',function(){
                var question_id = $(this).val();
                var SelectedQuestions = [];
                $(this).closest('#survey_questions').find('select')
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedQuestions.push(selVal);
                        })
                        .end()
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedQuestions.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedQuestions.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end()

                $('select#select_question_id')
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedQuestions.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedQuestions.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end()
            });

            $(document).on('change','select#select_question_id',function(event){
                var question_id = $(this).val();
                if( !question_id || typeof question_id == 'undefined' || question_id == ''){
                    return;
                }

                var options_string = "<ul>";
                $('input[name="'+question_id+'"]').each(function(){
                    var value_str = $(this).val();
                    // console.log(value_str);
                    var key_val_arr = value_str.split("=");
                    options_string += '<li>'+key_val_arr[0]+': '+key_val_arr[1]+'</li>';
                });

                options_string +="</ul>";
                $(this).closest('.input-area').find('.answer-list').html(options_string);
            });

            $(document).on('change','select.selected_question',function(event){
                var question_id = $(this).val();
                if( !question_id || typeof question_id == 'undefined' || question_id == ''){
                    $(this).closest('.input-area').find('.answer-list').html("");
                    return;
                }

                var options_string = "<ul>";
                $('input[name="'+question_id+'"]').each(function(){
                    var value_str = $(this).val();
                    // console.log(value_str);
                    var key_val_arr = value_str.split("=");
                    options_string += '<li>'+key_val_arr[0]+': '+key_val_arr[1]+'</li>';
                });

                options_string +="</ul>";
                $(this).closest('.input-area').find('.answer-list').html(options_string);
            });

            $(document).on('click','.remove',function(event){
                var question_id = $(this).closest('.question').find('select.selected_question').first().val();
                var questions = $('#survey_questions').find('select').length;
                if( questions <= 1 ){
                    JconfirmAlert('Notification','You need a least one question to create survey.');
                    return;
                }
                $(this).closest('.question').remove();
                question_count--;
                $('#survey_questions .question label[for="question_counter"]').each(function(i,v){
                    $(this).text('Question #'+(i+1));
                });

                $('#survey_questions').find('select')
                        .each(function(index, elem) {
                            $(elem).find('option[value="'+question_id+'"]').first().show();
                        })
                        .end()
                $('select#select_question_id').find('option[value="'+question_id+'"]').first().show();
            });



            $("#department_id").on('change',function(){
                var department_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "/surveys/getProjectUnderDepartment?department_id="+department_id,
                    async: false,
                    dataType: 'JSON',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (rows) {
                        var options = '<option value="NA">'+"--Select Project--"+'</option>';
                        for(var row in rows){
                            options += '<option value="'+rows[row].id+'">'+rows[row].name+'</option>';
                        }
                        $("#project_id").html(options);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // genericError(jqXHR, textStatus, errorThrown);
                    },
                    processData: false,
                });
            });
        });
    </script>
@endsection
@extends('layouts.app')
@section('title','Add Survey')
@section('content')

    <form class="form-horizontal" role="form" method="POST" action="{{ route('surveys.store') }}">
        {{ csrf_field() }}

        <div class="input-area {{ $errors->has('department') ? ' has-error' : '' }}">

            <label for="select">Department</label>
            <select name="department_id" id="department_id" >
                @foreach($departments as $department)
                    <option value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
            </select>

            @if ($errors->has('department'))
                <span class="help-block">
                                <strong>{{ $errors->first('department') }}</strong>
                </span>
            @endif
        </div>
        <div class="input-area {{ $errors->has('project_id') ? ' has-error' : '' }}">
            <label for="select">Project</label>
            <select  name="project_id" id="project_id" required>
                <option value="">--Select Project--</option>
                @foreach($projects as $project)
                    <option value="{{$project->id}}">{{$project->name}}</option>
                @endforeach
            </select>

            @if ($errors->has('project'))
                <span class="help-block">
                                <strong>{{ $errors->first('project') }}</strong>
                            </span>
            @endif
        </div>

        {{--<div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">--}}

            {{--<label for="select">Survey Name</label>--}}
            {{--<input id="name" type="text"  name="name" value="{{ old('name') }}" required>--}}

            {{--@if ($errors->has('name'))--}}
                {{--<span class="help-block">--}}
                                {{--<strong>{{ $errors->first('name') }}</strong>--}}
                            {{--</span>--}}
            {{--@endif--}}
        {{--</div>--}}
        {{--<div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">--}}
            {{--<label for="select">Remarks</label>--}}
            {{--<textarea id="remarks"  name="remarks" required>{{ old('remarks') }}</textarea>--}}

            {{--@if ($errors->has('remarks'))--}}
                {{--<span class="help-block">--}}
                                {{--<strong>{{ $errors->first('remarks') }}</strong>--}}
                            {{--</span>--}}
            {{--@endif--}}
        {{--</div>--}}


        <div  id="survey_questions"  >
        </div>


        <div class="input-area" >
            <label for="">
                <div class="section"><a type="button"  href="javacript:void(0);" class="fl add_question">+ Add Another Question</a></div>
            </label>

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

        <div class="form-group">
            <div class="input-area">
                <div class="col-md-6 col-md-offset-4">
                    <a href="{{ route('surveys') }}" class="big-btn">Go back</a>
                    <button type="submit">
                        Create Survey
                    </button>
                </div>
            </div>
        </div>
    </form>


@endsection

@section('FooterAdditionalCodes')
    <script type="text/javascript">
        $(document).ready(function(){


            var question_count = 1;
            $(document).on('click','.add_question',function(event){
                event.preventDefault();
                var question_id = $("#select_question_id").val();
                var SelectElement = $('<select class="selected_question" name="selected_question_'+question_count+'"/>').append($('#select_question_id').html());
                var outerSelectedHtml = $('<div/>').append(SelectElement).html();
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


                var question_div = '<div class="question">'+
                                        '<div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>'+
                                        '<div class="input-area">'+
                                            '<input type="hidden" name="question_'+question_id+'" value="'+question_id+'"/>'+
                                            '<label for="question_counter">Question #'+question_count+'</label>'+
                                            outerSelectedHtml+
                                            '<div class="answer-list seven columns fr">'+
                                                options_string+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';

                $("#survey_questions").append(question_div)
                                      .find('select[name="selected_question_'+question_count+'"]')
                                        .first()
                                            .find('option[value="'+question_id+'"]')
                                                .first()
                                                    .attr('selected',true);

                question_count++;
                $('#select_question_id option[value="'+question_id+'"]').hide();
                $('#select_question_id option[value="'+question_id+'"]').attr('selected',false);
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
                console.log(question_id);
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
@extends('layouts.app')
@section('title','SurveyQuestion')
@section('content')

                <form class="form-horizontal" role="form" method="POST" action="{{ route('surveyQuestions.store') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="survey_id" id="survey_id" value="{{ isset($survey) ? $survey->id:"" }}"/>

                    <div class="input-area">

                            <label for="select">Question Type</label>
                            <select  name="type" id="type" required>
                                <option value="">--Select Type--</option>
                                <option value="Numeric">Numeric</option>
                                <option value="Non-Numeric">Non-Numeric</option>
                            </select>

                        @if ($errors->has('type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="input-area {{ $errors->has('body') ? ' has-error' : '' }}">

                            <label for="select">Question Body</label>
                            <textarea id="body"  name="body" required>{{ old('body') }}</textarea>

                        @if ($errors->has('body'))
                            <span class="help-block">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="input-area {{ $errors->has('num_options') ? ' has-error' : '' }}">

                            <label for="num_options">Type Number of Answer Options</label>
                            <input id="num_options" type="number"  name="num_options" value="{{ old('num_options') }}" required>

                        @if ($errors->has('options'))
                            <span class="help-block">
                                <strong>{{ $errors->first('num_options') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div id="answer_options" class="answer_options">
                        {{--<div class="row form-group option_row_1">--}}
                            {{--<div class="col-md-2"></div>--}}
                            {{--<div class="col-md-1">--}}
                                {{--Key--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<input class="form-control" type="text" name="key_1" id="key_1"  />--}}
                            {{--</div>--}}
                            {{--<div class="col-md-1">--}}
                                {{--Value--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<input class="form-control"  type="text" name="value_1" id="value_1"  />--}}
                            {{--</div>--}}
                            {{--<div class="col-md-2"></div>--}}
                        {{--</div>--}}
                        {{--<div class="row form-group option_row_2">--}}
                            {{--<div class="col-md-2"></div>--}}
                            {{--<div class="col-md-1">--}}
                                {{--Key--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<input class="form-control" type="text" name="key_2" id="key_2"  />--}}
                            {{--</div>--}}
                            {{--<div class="col-md-1">--}}
                                {{--Value--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<input class="form-control"  type="text" name="value_2" id="value_2"  />--}}
                            {{--</div>--}}
                            {{--<div class="col-md-2"></div>--}}
                        {{--</div>--}}
                    </div>


                    <div class="row text-center input-area">
                      <a href="{{ route('surveyQuestions',['id'=>$survey->id]) }}" class="big-btn">Go back to list page</a>
                        <button type="submit">
                                Create Question
                        </button>
                    </div>

                </form>


@endsection

@section('FooterAdditionalCodes')
    <script type="text/javascript" src="{{asset(" js/Jconfirm.js ")}}"></script>
    <script type="text/javascript" src="{{asset(" js/JconfirmFunctions.js ")}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var ALPHABET_OPTION_ARRAY = [],
                    NUMARIC_OPTION_ARRAY = [];
            var alphabet_options_str, numeric_options_str;
            ALPHABET_OPTION_ARRAY.push('<option value="">--Select Key--</option>');
            var ch, el;
            for (var i = 65; i <= 90; i++) {
                ch = String.fromCharCode(i).toUpperCase();
                el = '<option value="' + ch + '">' + ch + '</option>';
                ALPHABET_OPTION_ARRAY.push(el)
            }
            alphabet_options_str = ALPHABET_OPTION_ARRAY.join("");
            ALPHABET_OPTION_ARRAY = [];
            NUMARIC_OPTION_ARRAY.push('<option value="">--Select Key--</option>');
            for (var j = 1; j <= 9; j++) {
                NUMARIC_OPTION_ARRAY.push('<option value="' + j + '">' + j + '</option>');
            }
            numeric_options_str = NUMARIC_OPTION_ARRAY.join("");
            NUMARIC_OPTION_ARRAY = [];
            $("#type").on("change", function() {
                var value = $(this).val();
                if (value == "Numeric") {
                    $("#answer_options").find("select").html(numeric_options_str);
                    $("#num_options").attr({
                        'min': 2,
                        'max': 9
                    });
                } else if (value == "Non-Numeric") {
                    $("#answer_options").find("select").html(alphabet_options_str);
                    $("#num_options").attr({
                        'min': 2,
                        'max': 26
                    });
                }

                if (!value || typeof value == 'undefined' || value == "") {
                    $("#answer_options").html("");
                    $("#num_options").val("");
                    return false;
                }
            });

            var IS_FIRST_TYPED_FINISHED = true;
            var TYPING_TIMER;
            var DONE_TYPING_INTERVAL = 3000; // in ms unit


            function finished_typing(eventObject) {
                //console.log("Obj:"+eventObject.value);
                if (TYPING_TIMER) clearInterval(TYPING_TIMER);
                var question_type = $("#type").val();
                if (!question_type || typeof question_type == 'undefined' || question_type == "") {
                    JconfirmAlert('Alert Notification', 'Please,Select question type first.');
                    $(eventObject).val("");
                    $("#answer_options").empty();
                    return false;
                }

                var num_options = $(eventObject).val();
                if (question_type === "Numeric") {
                    if (num_options && (num_options > 9 || num_options <= 1)) {
                        JconfirmAlert('Alert Notification', 'Answer options must be between 2 and 9 for Question type Numeric.');
                        $(eventObject).val("");
                        $("#answer_options").empty();
                        return false;
                    }
                }

                if (question_type === "Non-Numeric") {
                    if (num_options && (num_options > 26 || num_options <= 1)) {
                        JconfirmAlert('Alert Notification', 'Answer options must be between 2 and 26 for Question type Non-Numeric.');
                        $(eventObject).val("");
                        $("#answer_options").empty();
                        return false;
                    }
                }

                var length = $("#answer_options").children().length;
                if (num_options && num_options > length) {
                    // push addition row
                    var number_to_add = num_options - length;
                    var html_rows = "";
                    for (var i = 1; i <= number_to_add; i++) {
                        html_rows += '<div class="row form-group option_row_' + (length + i) + '">' +
                                '<div class="col-md-2"></div>' +
                                '<div class="col-md-1">Key</div>' +
                                '<div class="col-md-3"><select  name="key_' + (length + i) + '" id="key_' + (length + i) + '"></select></div>' +
                                '<div class="col-md-1">Value</div>' +
                                '<div class="col-md-3"><input  type="text" name="value_' + (length + i) + '" id="value_' + (length + i) + '" /></div>' +
                                '<div class="col-md-2"></div>' +
                                '</div>';
                    }

                    if (IS_FIRST_TYPED_FINISHED) $("#answer_options").append(html_rows).find('select').hide();
                    else
                        $("#answer_options").append(html_rows);
                } else if (num_options) {
                    // reduce number of row
                    var hides_row = length - num_options;
                    for (var i = 1; i <= hides_row; i++) {
                        $('#answer_options div.option_row_' + length).remove();
                        length--;
                    }
                }

                if (num_options) {
                    if (question_type == "Numeric") {
                        var selects = $("#answer_options").find("select").each(function(index, element) {
                            if ($(element).find('option').length === 0) {
                                $(element).html(numeric_options_str);
                            }
                        });

                    }
                    if (question_type == "Non-Numeric") {
                        var selects = $("#answer_options").find("select").each(function(index, element) {
                            if ($(element).find('option').length === 0) {
                                $(element).html(alphabet_options_str);
                            }
                        });
                    }
                    var SelectedValues = [];
                    $("#answer_options").find("select")
                            .each(function(index, elem) {
                                var selVal = $(elem).find('option:selected').val();
                                if (selVal != "") SelectedValues.push(selVal);
                            })
                            .end()
                            .each(function(index, elem) {
                                $(elem).find('option[value!=""]')
                                        .filter(function(index, option) {
                                            return SelectedValues.indexOf(option.value) !== -1;
                                        })
                                        .hide()
                                        .end()
                                        .filter(function(index, option) {
                                            return SelectedValues.indexOf(option.value) == -1;
                                        }).show()
                                        .end()

                            })
                            .end()




                    $("#answer_options").find("select").each(function(key, element) {
                        $(element).on('change', function(event) {
                            var SelectedValues = [];
                            $("#answer_options").find("select")
                                    .each(function(index, elem) {
                                        var selVal = $(elem).find('option:selected').val();
                                        if (selVal != "") SelectedValues.push(selVal);
                                    })
                                    .end()
                                    .each(function(index, elem) {
                                        $(elem).find('option[value!=""]')
                                                .filter(function(index, option) {
                                                    return SelectedValues.indexOf(option.value) !== -1;
                                                })
                                                .hide()
                                                .end()
                                                .filter(function(index, option) {
                                                    return SelectedValues.indexOf(option.value) == -1;
                                                })
                                                .show()
                                                .end()

                                    })
                                    .end()

                        });
                    });


                    if (IS_FIRST_TYPED_FINISHED) {
                        IS_FIRST_TYPED_FINISHED = false;
                        var options = $("#answer_options").find("select").first().find("option").filter('option[value !=""]');
                        $("#answer_options").find("select").each(function(index, element) {
                            if (index < options.length) {
                                $(element).find('option[value="' + options[index].value + '"]').attr('selected', true);
                                $(element).trigger('change');
                            }
                            $(element).show().delay(5000);
                        });
                    }

                }
            }

            $("#num_options").keydown(function(event) {
                if (TYPING_TIMER) clearInterval(TYPING_TIMER);
            });

            $("#num_options").keyup(function() {
                if (TYPING_TIMER) clearInterval(TYPING_TIMER);
                TYPING_TIMER = setInterval(finished_typing, DONE_TYPING_INTERVAL, this);
            });
        });
    </script>
@endsection
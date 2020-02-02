@extends('layouts.app')
@section('title', 'SurveyQuestion')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ "Question Delete Confirmation." }}</div>
                <div class="panel-body">
                    <div class="row text-center">
                        {!! $surveyQuestion->body !!}
                    </div>
                    <br />
                    <div id="answer_options" class="answer_options">

                        @foreach($surveyQuestion->answerOptions as $index=>$option)
                            <div class="row form-group option_row_{{$index+1}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-1">
                                    Key
                                </div>
                                <div class="col-md-3">
                                    <input readonly value="{{$option->key}}"  type="text" name="key_{{$index+1}}" id="key_{{$index+1}}"  />
                                </div>
                                <div class="col-md-1">
                                    Value
                                </div>
                                <div class="col-md-3">
                                    <input readonly value="{{$option->body}}"  type="text" name="value_{{$index+1}}" id="value_{{$index+1}}"  />
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        @endforeach
                    </div>
                    <hr />
                    <form  method="POST" action="{{ route('surveyQuestions.confirmDelete', ['id' => $surveyQuestion->id]) }}">
                        {{ csrf_field() }}

                            <div class="row text-center input-area">

                                    <a href="{{ route('surveyQuestions',['id'=>$surveyQuestion->survey->id]) }}" class="big-btn">Go back</a>
                                    <button type="submit">
                                        Confirm Delete
                                    </button>

                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
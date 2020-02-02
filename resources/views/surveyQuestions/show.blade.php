@extends('layouts.app')
@section('title','SurveyQuestion')
@section('content')

                <form class="form-horizontal" role="form" action="#">
                    {{ csrf_field() }}

                    <div class="input-area">
                            <label for="select">Question Type</label>
                            <select disabled  name="type" id="type" required>
                                <option value="">--Select Type--</option>
                                <option {{ $surveyQuestion->type == "Numeric"?"selected":"" }} value="Numeric">Numeric</option>
                                <option {{ $surveyQuestion->type == "Non-Numeric"?"selected":"" }} value="Non-Numeric">Non-Numeric</option>
                            </select>

                        @if ($errors->has('type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="input-area {{ $errors->has('body') ? ' has-error' : '' }}">

                            <label for="select">Question Body</label>
                            <textarea readonly  id="body"  name="body" required>{{ $surveyQuestion->body  }}</textarea>

                        @if ($errors->has('body'))
                            <span class="help-block">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="input-area">
                            <label for="select">Type Number of Answer Options</label>
                            <input readonly id="num_options" type="number" name="num_options" value="{{ $surveyQuestion->answerOptions->count()  }}" required>

                            @if ($errors->has('options'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('num_options') }}</strong>
                                </span>
                            @endif
                    </div>
                    <div id="answer_options" class="answer_options">

                        @foreach($surveyQuestion->answerOptions as $option)
                            <div class="row form-group option_row_{{$option->id}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-1">
                                    Key
                                </div>
                                <div class="col-md-3">
                                    <input  readonly value="{{$option->key}}" type="text" name="key_{{$option->id}}" id="key_{{$option->id}}"  />
                                </div>
                                <div class="col-md-1">
                                    Value
                                </div>
                                <div class="col-md-3">
                                    <input  readonly value="{{$option->body}}" class="value_field" type="text" name="value_{{$option->id}}" id="value_{{$option->id}}"  />
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        @endforeach

                    </div>

                    <div class="row text-center input-area">
                        <a href="{{ route('surveyQuestions.edit', ['id' => $surveyQuestion->id]) }}" class="big-btn yellowbtn">Edit</a>
                        <a href="{{ route('surveyQuestions.delete', ['id' => $surveyQuestion->id]) }}" class="big-btn redbtn">Delete</a>
                        <a href="{{ route('surveyQuestions.index' , ['id' => $surveyQuestion->survey_id]) }}" class="big-btn">Go back</a>
                    </div>
                </form>


@endsection
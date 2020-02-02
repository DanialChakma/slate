@extends('layouts.app')
@section('title','SurveyQuestion')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <form class="form-horizontal" role="form" action="#">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <div class="input-area">
                            <label for="select">Question Type</label>
                            <select disabled class="select-chosen" name="type" id="type" required>
                                <option value="">--Select Type--</option>
                                <option {{ $question->type == "Numeric"?"selected":"" }} value="Numeric">Numeric</option>
                                <option {{ $question->type == "Non-Numeric"?"selected":"" }} value="Non-Numeric">Non-Numeric</option>
                            </select>
                        </div>
                        @if ($errors->has('type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                        <div class="input-area">
                            <label for="select">Question Body</label>
                            <textarea readonly  id="body" class="body" name="body" required>{{ $question->body  }}</textarea>
                        </div>
                        @if ($errors->has('body'))
                            <span class="help-block">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="input-area">
                            <label for="select">Type Number of Answer Options</label>
                            <input readonly id="num_options" type="number" class="num_options" name="num_options" value="{{ $question->answerOptions->count()  }}" required>
                        </div>
                        @if ($errors->has('options'))
                            <span class="help-block">
                                <strong>{{ $errors->first('num_options') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div id="answer_options" class="answer_options">

                        @foreach($question->answerOptions as $option)
                            <div class="row form-group option_row_{{$option->id}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-1">
                                    Key
                                </div>
                                <div class="col-md-3">
                                    <input readonly value="{{$option->key}}" type="text" name="key_{{$option->id}}" id="key_{{$option->id}}"  />
                                </div>
                                <div class="col-md-1">
                                    Value
                                </div>
                                <div class="col-md-3">
                                    <input readonly value="{{$option->body}}" class="value_field" type="text" name="value_{{$option->id}}" id="value_{{$option->id}}"  />
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        @endforeach

                    </div>

                    <div class="form-group">
                        <div class="input-area">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('surveyQuestions',['id'=>$question->survey->id]) }}" class="btn btn-primary btn-lg">Go back to list page</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@extends('layouts.app')
@section('title','SurveyQuestion')
@section('content')

    <form class="form-horizontal" role="form" action="#">
        {{ csrf_field() }}

        <div class="input-area">
            <label for="select">Question Type</label>
            <select disabled  name="type" id="type" required>
                <option value="">--Select Type--</option>
                <option {{ $question->type == "Numeric"?"selected":"" }} value="Numeric">Numeric</option>
                <option {{ $question->type == "Non-Numeric"?"selected":"" }} value="Non-Numeric">Non-Numeric</option>
            </select>

            @if ($errors->has('type'))
                <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
            @endif
        </div>

        <div class="input-area {{ $errors->has('body') ? ' has-error' : '' }}">

            <label for="select">Question</label>
            <textarea readonly  id="body"  name="body" required>{{ $question->body  }}</textarea>

            @if ($errors->has('body'))
                <span class="help-block">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
            @endif
        </div>
        <div class="input-area">
            <label for="select">Answer Options</label>
            <input readonly id="num_options" type="number" name="num_options" value="{{ $question->answerOptions->count()  }}" required>

            @if ($errors->has('options'))
                <span class="help-block">
                                    <strong>{{ $errors->first('num_options') }}</strong>
                                </span>
            @endif
        </div>
        <div id="answer_options" class="answer_options" style="float: right;">

            @foreach($question->answerOptions as $option)
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
            <a href="{{ route('questions.edit', ['id' => $question->id]) }}" class="big-btn yellowbtn">Edit</a>
            <a href="{{ route('questions.delete', ['id' => $question->id]) }}" class="big-btn redbtn">Delete</a>
            <a href="{{ route('questions') }}" class="big-btn">Back</a>

        </div>
    </form>
@endsection
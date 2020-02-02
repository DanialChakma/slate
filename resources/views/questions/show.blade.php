@extends( 'layouts.app' )
@php
$title = "Survey Question";
@endphp

@section('title',$title)
@section('HeaderAdditionalCodes')
	<script>
		var parentClasses = ["a_questions"];
	</script>
@endsection
@section( 'content' )
<h1>{{ $title }}</h1>
<form class="form-horizontal" role="form" action="#">
	{{ csrf_field() }}

	<div class="input-area">
		<label for="select">Question Type</label>
		<select disabled name="type" id="type" required>
			<option value="">--Select Type--</option>
			<option {{ $question->type == "Numeric"?"selected":"" }} value="Numeric">Numeric</option>
			<option {{ $question->type == "Non-Numeric"?"selected":"" }} value="Non-Numeric">Non-Numeric</option>
			<option {{ $question->type == "Open-text"?"selected":"" }} value="Open-text">Open-text</option>
		</select>

		@if ($errors->has('type'))
		<span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
		@endif
	</div>

	<div class="input-area {{ $errors->has('body') ? ' has-error' : '' }}">

		<label for="select">Question</label>
		<textarea readonly id="body" name="body" required>{{ $question->body  }}</textarea> @if ($errors->has('body'))
		<span class="help-block">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
		@endif
	</div>
	<div class="input-area">
		<label for="select">Answer Options</label>
		<input readonly id="num_options" type="number" name="num_options" value="{{ $question->answerOptions->count()  }}" required> @if ($errors->has('options'))
		<span class="help-block">
                                    <strong>{{ $errors->first('num_options') }}</strong>
                                </span>
		@endif
	</div>
	<div id="answer_options" class="answer_options">

		@foreach($question->answerOptions as $option)
		<div class="row option_row_{{$option->id}}">
			<div class="six columns">
			<span>Key</span><input readonly value="{{$option->key}}" type="text" name="key_{{$option->id}}" id="key_{{$option->id}}"/>
			</div>
			<div class="six columns">
			<span>Value</span> <input readonly value="{{$option->body}}" class="value_field" type="text" name="value_{{$option->id}}" id="value_{{$option->id}}"/>
			</div>
		</div>
		@endforeach

	</div>

	 <div class="button-area">
		<a href="{{ route('questions.edit', ['id' => $question->id]) }}" class="big-btn yellowbtn">Edit</a>
		<a href="{{ route('questions.delete', ['id' => $question->id]) }}" class="big-btn redbtn">Delete</a>
		<a href="{{ route('questions') }}" class="big-btn">Back</a>
	</div>
</form>
@endsection
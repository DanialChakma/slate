@extends('layouts.app')

@php
$title = "Survey Operation Status";
@endphp

@section('title',$title)
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_survey"];
    </script>
@endsection
@section('content')
   <h1> {{ $survey->name }}</h1>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="section company-details list-items">
                       <ul> <li><strong>Department</strong>  {{ $survey->department->name }}</li>
						<li><strong>Project</strong> {{ $survey->project->name }}</li>
						<li><strong>Name</strong> {{ $survey->name }}</li>
						<li><strong>Remarks</strong> {{ $survey->remarks }}</li></ul>
                    </div>
                   
			 @foreach($survey->questions as $question)
                    <div class="section person-details list-items">
                    <h2>Questions</h2>
					<h3>{{ $question->body }}</h3>
					  <ul>  
						  @foreach($question->answerOptions as $answerOption)
					   <li><span>{{ $answerOption->key }}</span>{{ $answerOption->body }}</li>
								@endforeach
					   </ul>
					</div>
                            @endforeach
                </div>
            </div>

            <div class="button-area">
                <a href="{{ route('surveys.edit', ['id' => $survey->id]) }}" class="big-btn yellowbtn">Edit</a>
                <a href="{{ route('surveys.delete', ['id' => $survey->id]) }}" class="big-btn redbtn">Delete</a>
                <a href="{{ route('surveys') }}" class="big-btn" >Back</a>
            </div>
      
@endsection
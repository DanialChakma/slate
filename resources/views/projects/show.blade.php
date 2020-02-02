@extends('layouts.app')

@section('title',$project->name)
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_project"];
    </script>
@endsection
@section('content')
   <h1>{{ $project->name }}</h1>
    <div class="section company-details list-items">
       
         <ul> <li><strong>Project Name</strong> {{ $project->name }}</li>
			<li><strong>Department</strong> {{ $project->department->name }}</li>
			<li><strong>Remarks</strong> {{ $project->description }}</li></ul>
      </div>

          <div class="button-area">
                <a href="{{ route('projects.edit', ['id' => $project->id]) }}" class="big-btn yellowbtn">Edit</a>
                <a href="{{ route('projects.delete', ['id' => $project->id]) }}" class="big-btn redbtn">Delete</a>
                <a href="{{ route('projects') }}" class="big-btn">Back</a>
            </div>
        
@endsection
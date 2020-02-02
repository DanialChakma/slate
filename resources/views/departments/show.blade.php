@extends('layouts.app')

@php
$title = $department->name;
@endphp
@section('title',$title)
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_department"];
    </script>
@endsection
@section('content')
    <h1>{{ $title }}</h1>
   <div class="section show-destails list-items">
  <ul><li><strong>Department Name</strong> {{ $department->name }}</li>
                    
<li><strong>Remarks</strong> {{ $department->description ?? 'N/A' }}</li></ul>
            </div>
           <div class="button-area">
             <a href="{{ route('departments.edit', ['id' => $department->id]) }}" class="big-btn yellowbtn">Edit</a>
            <a href="{{ route('departments.delete', ['id' => $department->id]) }}" class="big-btn redbtn">Delete</a>
            <a href="{{ route('departments') }}" class="big-btn">Back</a></div>
      
@endsection
@extends('layouts.app')

@php
$title = "Edit Project";
@endphp

@section( 'title', $title )

@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_project"];
    </script>
@endsection
@section('content')
<h1>{{ $title }}</h1>

            <form class="form-horizontal" role="form" method="POST" action="{{ route('projects.update', ['id' => $project->id]) }}">
                {{ csrf_field() }}

                <input name="id" type="hidden" value="{{ $project->id }}" />

                <div class="form-group">
                    <div class="input-area">
                        <label for="select">Department <span class="required">*</span></label>
                        <select name="department_id" id="department">
                            @foreach(\App\Department::all() as $department)
                                @if( $project->department->id  == $department->id )
                                    <option selected value="{{$department->id}}">{{$department->name}}</option>
                                @else
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-area{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>
                    <input id="name" type="text" name="name" value="{{ empty(old('name')) ? $project->name : old('name') }}" required autofocus>

                </div>

                @if ($errors->has('name'))
                    <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                @endif

                <div class="input-area{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description">Remarks</label>

                    <textarea id="description"  name="description">{{ empty(old('description')) ? $project->description : old('description') }}</textarea>

                </div>

                @if ($errors->has('description'))
                    <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                @endif

               
   	<div class="button-area">
			<a href="{{ route('projects') }}" class="big-btn">Back</a>
                        <input type="submit" class="sbtn" value="Update"></div>
</form>

@endsection
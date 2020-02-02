@extends('layouts.app')
@section('title','Survey Details')
@section('content')

                <form class="form-horizontal" role="form" action="#">
                    {{ csrf_field() }}

                    <div class="input-area">

                            <label for="select">Department</label>
                            <input readonly id="department" type="text"  name="department" value="{{ (isset($survey) && !empty($survey->department_id))? $survey->department->name:"" }}">

                    </div>
                    <div class="input-area">

                            <label for="select">Project</label>
                            <input id="project_id" type="text"  name="project_id" value="{{ (isset($survey) && !empty($survey->project_id))? $survey->project->name:"" }}" readonly>

                    </div>

                    <div class="input-area">

                            <label for="select">Survey Name</label>
                            <input readonly id="title" type="text" class="form-control" name="title" value="{{ (isset($survey) && !empty($survey->name))? $survey->name:"" }}">

                    </div>
                    <div class="input-area">
                        <label for="select">Remarks</label>
                        <textarea readonly id="remarks" class="form-control" name="remarks">{{ (isset($survey) && !empty($survey->remarks)) ? $survey->remarks:"" }}</textarea>
                    </div>

                </form>
                <div class="row input-area">
                            <a href="{{ route('surveys.edit', ['id' => $survey->id]) }}" class="big-btn yellowbtn">Edit</a>
                            <a href="{{ route('surveys.delete', ['id' => $survey->id]) }}" class="big-btn redbtn">Delete</a>
                            <a href="{{ route('surveys.index') }}" class="big-btn">Back</a>
                </div>
@endsection
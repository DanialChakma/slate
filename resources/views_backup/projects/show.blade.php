@extends('layouts.app')

@section('title',$project->name)

@section('content')
    <div class="section content-area">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td class="col-xs-2">Project Name</td>
                    <td>{{ $project->name }}</td>
                </tr>
                <tr>
                    <td class="col-xs-2">Department</td>
                    <td>{{ $project->department->name }}</td>
                </tr>
                <tr>
                    <td class="col-xs-2">Remarks</td>
                    <td>{{ $project->description }}</td>
                </tr>
            </table>
        </div>

        <br/>
        <div class="row">
            <div class="col-sm-4"></div>

            <div class="col-sm-8">
                <a href="{{ route('projects.edit', ['id' => $project->id]) }}" class="big-btn yellowbtn">Edit</a>
                <a href="{{ route('projects.delete', ['id' => $project->id]) }}" class="big-btn redbtn">Delete</a>

                <a href="{{ route('projects.index') }}" class="big-btn">Back</a>

            </div>
        </div>
    </div>


@endsection
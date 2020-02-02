@extends('layouts.app')

@section('title', 'Delete Project')
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_project"];
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">

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
            <br />
            <form class="form-horizontal" role="form" method="POST" action="{{ route('projects.confirmDelete', ['id' => $project->id]) }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-1">
                            <button type="submit" class="sbtn">
                                Confirm Delete
                            </button>
                            <a href="{{ route('projects') }}" class="big-btn">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
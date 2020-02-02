@extends('layouts.app')

@section('title','List of projects')

@section('content')
    <div class="row">
        <div class="col-xs-6">
            <a class="btn btn-md btn-primary" href="{{ route('projects.create') }}">Add New Project</a>
        </div>
        <div class="col-xs-6">
            <form action="{{ route('projects.search') }}" method="GET" role="search">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search Project">
            <span class="input-group-btn">
            <button type="submit" class="btn btn-default">
                <span class="glyphicon glyphicon-search"></span>
            </button>
            </span>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8">
            @if(isset($projects))
                @if(isset($q))
                    {{ $projects->appends(['q'=>$q])->links() }}
                @else
                    {{ $projects->links() }}
                @endif
            @endif
        </div>
        <div class="col-xs-4">
            <span class="pagination">
                {{ "Total projects found:".$projects->total() }}
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                @if(isset($projects) && count($projects)> 0 )
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>Sl.</td>
                        <th>Name</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->name }}</td>
                            <td> {{ str_limit($project->description, $limit = 60, $end = '...') }} </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('projects.show', ['id' => $project->id]) }}">Details</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('projects.edit', ['id' => $project->id]) }}">Edit</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('projects.delete', ['id' => $project->id]) }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-info">{{$msg}}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if(isset($projects))
                @if(isset($q))
                    {{ $projects->appends(['q'=>$q])->links() }}
                @else
                    {{ $projects->links() }}
                @endif
            @endif

        </div>
    </div>
@endsection
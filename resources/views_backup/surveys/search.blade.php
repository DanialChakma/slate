@extends('layouts.app')
@section('title','List of Surveys')
<style>
    .table td {
        overflow: hidden; /* this is what fixes the expansion */
        text-overflow: ellipsis; /* not supported in all browsers, but I accepted the tradeoff */
        white-space: nowrap;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-xs-6">
            <a class="big-btn" href="{{ route('surveys.create') }}">Add New Survey</a>
        </div>
        <div class="col-xs-6">
            <form action="{{ route('surveys.search') }}" method="GET" role="search">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" class="form-control" name="q"
                           placeholder="Search Survey">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-secondary">
                                Search
                            </button>
                        </span>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8">
            @if(isset($surveys))

                @if(isset($q))
                    {{ $surveys->appends(['q'=>$q])->links() }}
                @else
                    {{ $surveys->links() }}
                @endif

            @endif
        </div>
        <div class="col-xs-4">
            @if(isset($surveys))
                <span class="pagination">{{ "Total surveys found:".$surveys->total() }}</span>
            @endif
        </div>

    </div>
    <div class="row">
        <div class="col-xs-12">
            @if( isset($surveys) && count($surveys) > 0 )
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>Sl.</td>
                            <th>Name</th>
                            <th>Remarks</th>

                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($surveys as $row)
                            <tr>
                                <td >{{ $row->id }}</td>
                                <td style="overflow:hidden;max-width:200px;text-overflow:ellipsis;">{{ $row->name }}</td>
                                <td style="overflow:hidden;max-width:300px;text-overflow:ellipsis;">{{ $row->remarks }}</td>

                                <td>
                                    <a class="action-btn" href="{{ route('surveys.show', ['id' => $row->id]) }}">
                                        Details
                                    </a>
                                    <a class="action-btn" href="{{ route('surveys.edit', ['id' => $row->id]) }}">
                                        Edit
                                    </a>
                                    <a class="action-btn" href="{{ route('surveys.delete', ['id' => $row->id]) }}">
                                        Delete
                                    </a>
                                    <a class="action-btn" href="{{ route('surveyQuestions', ['id' => $row->id]) }}">
                                        Questions
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif( isset($msg) )
                <div class="text-info">{{$msg}}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if(isset($surveys))
                @if(isset($q))
                    {{ $surveys->appends(['q'=>$q])->links() }}
                @else
                    {{ $surveys->links() }}
                @endif
            @endif
        </div>
    </div>
@endsection
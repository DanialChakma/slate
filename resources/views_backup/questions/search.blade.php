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
            <a class="big-btn" href="{{ route('questions.create') }}">Add New Question</a>
        </div>
        <div class="col-xs-6">
            <form action="{{route('questions.search')}}" method="GET" role="search">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" class="form-control" name="q"
                           placeholder="Search Question">
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
        <div class="col-xs-6">
            @if(isset($questions))
                @if(isset($q))
                    {{ $questions->appends(["q"=>$q])->links() }}
                @else
                    {{ $questions->links() }}
                @endif
            @endif
        </div>
        <div class="col-xs-6">
            @if(isset($questions))
                <span class="pagination">
                     {{ "Total Questions found: ".$questions->total() }}
                </span>

            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if( isset($questions) && count($questions) > 0 )
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>Sl.</td>
                            <th>Type</th>
                            <th>Body</th>

                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($questions as $row)
                            <tr>
                                <td >{{ $row->id }}</td>
                                <td style="overflow:hidden;max-width:200px;text-overflow:ellipsis;">{{ $row->type }}</td>
                                <td style="overflow:hidden;max-width:300px;text-overflow:ellipsis;">{{ $row->body }}</td>

                                <td>
                                    <a class="action-btn" href="{{ route('questions.show', ['id' => $row->id]) }}">
                                        Details
                                    </a>
                                    <a class="action-btn" href="{{ route('questions.edit', ['id' => $row->id]) }}">
                                        Edit
                                    </a>
                                    <a class="action-btn" href="{{ route('questions.delete', ['id' => $row->id]) }}">
                                        Delete
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
        <div class="col-xs-12 fr">
            @if(isset($questions) && count($questions)> 0 )
                @if(isset($q))
                    {{ $questions->appends(['q'=>$q])->links() }}
                @else
                    {{ $questions->links() }}
                @endif
            @endif
        </div>
    </div>
@endsection
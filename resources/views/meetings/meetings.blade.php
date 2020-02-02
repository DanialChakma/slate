@extends('layouts.app')
@section('title','List of My Meetings')
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
            {{--<a class="btn btn-md btn-primary" href="{{ route('meetings.create') }}">Add New Meeting Schedule</a>--}}
        </div>
        <div class="col-xs-6">
            <form action="{{ route('meetings.search') }}" method="GET" role="search">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" class="form-control" name="q"
                           placeholder="Search Meeting...">
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
        <div class="col-xs-12">
            @if(isset($meetings))
                {{ $meetings->links() }}
            @endif
        </div>
        {{--
        <div class="col-xs-4">
            <span class="pagination">
                @if(isset($meetings))
                    {{ "Total Meetings found:".$meetings->total() }}
                @endif
            </span>
        </div> --}}
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if( isset($meetings) && count($meetings) > 0 )
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>Sl.</td>
                            <th>Title</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($meetings as $row)
                            <tr>
                                <td >{{ $row->id }}</td>
                                <td >{{ str_limit( $row->remarks,30,'...') }}</td>
                                <td >{{ str_limit( $row->remarks,26,'...') }}</td>
                                <td style="overflow:hidden;max-width:300px;text-overflow:ellipsis;">{{ $row->status }}</td>
                                <td>
                                    <a class="action-btn" href="{{ route('meetings.changeStatus', ['id' => $row->id]) }}">
                                        Change Status
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
            @if(isset($meetings))
                {{ $meetings->links() }}
            @endif
        </div>
    </div>
@endsection
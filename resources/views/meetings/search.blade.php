@extends('layouts.app')
@section('title','List of Meetings')
<style>
    .table td {
        overflow: hidden; /* this is what fixes the expansion */
        text-overflow: ellipsis; /* not supported in all browsers, but I accepted the tradeoff */
        white-space: nowrap;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-xs-6 text-left">
            <form action="{{ route('meetings.search') }}" method="GET" role="search">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" class="form-control" name="q"
                           placeholder="Search Meeting">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-secondary">
                            Search
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-xs-6 text-right">
            <a class="big-btn" href="{{ route('meetings.create') }}">Add New Schedule</a>
        </div>
    </div>
    <br/>
    {{--<div class="row">--}}
        {{--<div class="col-xs-6">--}}
            {{--@if(isset($meetings) && count($meetings)> 0 )--}}
                {{--@if(isset($q))--}}
                    {{--{{ $meetings->appends(['q'=>$q])->links() }}--}}
                {{--@else--}}
                    {{--{{ $meetings->links() }}--}}
                {{--@endif--}}

            {{--@endif--}}
        {{--</div>--}}
        {{--<div class="col-xs-6">--}}
            {{--<span class="pagination">--}}
                {{--@if(isset($meetings))--}}
                    {{--{{ "Total Meetings found:".$meetings->total() }}--}}
                {{--@endif--}}
            {{--</span>--}}
        {{--</div>--}}
    {{--</div>--}}
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
                                <td style="max-width: 250px;" data-toggle="tooltip" title="{{$row->title}}" >{{ $row->title }}</td>
                                <td style="max-width: 350px;" data-toggle="tooltip" title="{{$row->remarks}}" >{{ $row->remarks }}</td>
                                <td data-toggle="tooltip" title="{{$row->status}}">{{ $row->status }}</td>
                                <td class="action">
                                    <a class="action-btn" href="{{ route('meetings.show', ['id' => $row->id]) }}">
                                        Details
                                    </a>
                                    <a class="action-btn" href="{{ route('meetings.edit', ['id' => $row->id]) }}">
                                        Edit
                                    </a>
                                    <a class="action-btn" href="{{ route('meetings.delete', ['id' => $row->id]) }}">
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
        <div class="col-xs-6 text-left">
            <span><strong>
                    @if(isset($meetings))
                        {{ "Total Meetings:".$meetings->total() }}
                    @endif
                </strong>
            </span>
        </div>
        <div class="col-xs-6 text-right">
            @if(isset($meetings) && count($meetings)> 0 )
                @if(isset($q))
                    {{ $meetings->appends(['q'=>$q])->links() }}
                @else
                    {{ $meetings->links() }}
                @endif
            @endif
        </div>
    </div>
@endsection
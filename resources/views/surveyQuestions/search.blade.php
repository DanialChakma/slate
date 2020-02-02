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
        <div class="col-xs-12">
            @if(isset($surveyQuestions))
            @if($surveyQuestions->count() > 0 )
            <a class="big-btn" href="{{ route('surveyQuestions.create',['id'=>$surveyQuestions->first()->survey_id]) }}">Add New Question</a>
            @endif
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            @if(isset($surveyQuestions))
                {{ "Total rows:".$surveyQuestions->total() }}
            @endif
        </div>
        <div class="col-xs-6">
            <form action="{{route('surveyQuestions.search')}}" method="GET" role="search">
                {{ csrf_field() }}
                <input name="survey_id" id="survey_id" type="hidden" value="{{$survey_id}}"/>
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
        <div class="col-xs-12">
            @if( isset($surveyQuestions) && count($surveyQuestions) > 0 )
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
                        @foreach ($surveyQuestions as $row)
                            <tr>
                                <td >{{ $row->id }}</td>
                                <td style="overflow:hidden;max-width:200px;text-overflow:ellipsis;">{{ $row->type }}</td>
                                <td style="overflow:hidden;max-width:300px;text-overflow:ellipsis;">{{ $row->body }}</td>

                                <td>
                                    <a class="action-btn" href="{{ route('surveyQuestions.show', ['id' => $row->id]) }}">
                                        Details
                                    </a>
                                    <a class="action-btn" href="{{ route('surveyQuestions.edit', ['id' => $row->id]) }}">
                                       Edit
                                    </a>
                                    <a class="action-btn" href="{{ route('surveyQuestions.delete', ['id' => $row->id]) }}">
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
        <div class="col-xs-12">
            @if(isset($surveyQuestions) && count($surveyQuestions)> 0 )
                @if(isset($q))
                    {{ $surveyQuestions->appends(['q'=>$q])->links() }}
                @else
                    {{ $surveyQuestions->links() }}
                @endif
            @endif
        </div>
    </div>
@endsection
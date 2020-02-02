@extends('layouts.app')
@section('title', 'Delete Meeting Schedule')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $meeting->title }}</div>
                <div class="panel-body">
                    {!! $meeting->remarks !!}
                    <br />
                    <hr />
                    <form  method="POST" action="{{ route('meetings.confirmDelete', ['id' => $meeting->id]) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="row text-center">
                                <button type="submit">
                                        Confirm Delete
                                </button>
                                <a href="{{ route('meetings.index') }}" class="big-btn">Back</a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
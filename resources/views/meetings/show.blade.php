@extends('layouts.app')
@section('title',"Meeting Operation Status.")
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_meetings"];
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Meeting Operation Status.</div>
                <div class="panel-body">
                    @if(isset($status) && !empty($status) )
                        {{$message}}
                    @else
                        {{$message}}
                    @endif
                    <br />
                    <hr>
                    <a href="{{ route('meetings') }}" class="btn btn-primary btn-lg">Go back to list page</a>
                </div>
            </div>
        </div>
    </div>
@endsection
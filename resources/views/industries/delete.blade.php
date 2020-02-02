@extends('layouts.app')

@section('title', 'Delete Industry')
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_industry"];
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Industry Information</div>
                <div class="panel-body">
                    Name: {{ $industry->name }}
                    <br />
                    <hr />
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('industries.confirmDelete', ['id' => $industry->id]) }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="sbtn">
                                        Confirm Delete
                                    </button>
                                    <a href="{{ route('industries') }}" class="big-btn">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
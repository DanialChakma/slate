@extends('layouts.app')

@section('title',$industry->name)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Industry Information</div>
                <div class="panel-body">
                    Name: {{ $industry->name }}
                    <br />
                    <hr>
                    <a href="{{ route('industries.edit', ['id' => $industry->id]) }}" class="big-btn yellowbtn">Edit</a>
                    <a href="{{ route('industries.delete', ['id' => $industry->id]) }}" class="big-btn redbtn">Delete</a>
                    <a href="{{ route('industries.index') }}" class="big-btn">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
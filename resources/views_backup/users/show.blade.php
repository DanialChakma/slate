@extends('layouts.app')

@section('title',$user->name)

@section('content')

    <h1>User: {{ $user->name }}</h1>
    <div class="section content-area">

        <div class="input-area">
            <label for="name">Name</label>
            <span>{{ $user->name }}</span>
        </div>

        <div class="input-area">
            <label for="description">Email</label>
            <span>{{ $user->email }}</span>
        </div>
        <div class="input-area">
            <label for="description">Phone</label>
            <span>{{ $user->phone }}</span>
        </div>
        <div class="input-area">
            <label for="description">Role</label>
            <span>{{ $user->role->name }}</span>
        </div>
        <div class="input-area">
            <label for="description">Department</label>
            <span>{{ $user->department->name }}</span>
        </div>
        <div class="input-area">
            <label for="description">Line Manager</label>
            <span>{{ empty($user->supervisor) ? "N/A" : $user->supervisor->name . ' (' . $user->supervisor->role->name . ')' }}</span>
        </div>
        <br/>
        <hr>

        <div class="row text-center input-area">
            <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="big-btn yellowbtn">Edit</a>
            <a href="{{ route('users.delete', ['id' => $user->id]) }}" class="big-btn redbtn">Delete</a>
            <a href="{{ route('users.index') }}" class="big-btn">Back</a>
        </div>
    </div>


@endsection
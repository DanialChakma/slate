@extends('layouts.app')

@section('title',$user->name)
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_user"];
    </script>
@endsection
@section('content')

    <h1>{{ $user->name }}</h1>
   

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
       

        <div class="button-area">
            <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="big-btn yellowbtn">Edit</a>
            <a href="{{ route('users.delete', ['id' => $user->id]) }}" class="big-btn redbtn">Delete</a>
            <a href="{{ route('users') }}" class="big-btn">Back</a>
        </div>
 


@endsection
@extends('layouts.app')

@section('title', 'Delete User')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">User Information</div>
                <div class="panel-body">

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
                    <br />
                    <hr />
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('users.confirmDelete', ['id' => $user->id]) }}">
                        {{ csrf_field() }}

                            <div class="row text-center input-area">
                                <button type="submit">
                                        Confirm Delete
                                </button>
                                <a href="{{ route('users.index') }}" class="big-btn">Back</a>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
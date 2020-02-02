@extends('layouts.app')

@php
$title = "Add Project";
@endphp

@section( 'title', $title )

@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_project"];
    </script>
@endsection
@section('content')

<h1>{{ $title }}</h1>

            <form class="form-horizontal" role="form" method="POST" action="{{ route('projects.store') }}">
                {{ csrf_field() }}


                <div class="input-area">
                    <label for="select">Department <span class="required">*</span></label>
                    <select name="department_id" id="department">
                        @foreach($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('department'))
                    <span class="help-block">
                        <strong>{{ $errors->first('department') }}</strong>
                       </span>
                @endif

                <div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

                </div>

                @if ($errors->has('name'))
                    <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                @endif

                <div class="input-area{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description">Remarks</label>
                    <textarea id="description"  name="description" value="{{ old('description') }}"></textarea>
                </div>

                @if ($errors->has('description'))
                    <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                @endif

                <div class="button-area">
                         <a href="{{ route('projects') }}" class="big-btn">Back</a>
                        <input type="submit" class="sbtn" value="Create">
                    </div>
            </form>
       

@endsection
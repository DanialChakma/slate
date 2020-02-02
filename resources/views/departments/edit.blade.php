@extends('layouts.app')

@php
$title = "Edit Department";
@endphp

@section('title',$title)

@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_department"];
    </script>
@endsection
@section('content')

 <h1>{{ $title }}</h1>

            <form class="form-horizontal" role="form" method="POST" action="{{ route('departments.update', ['id' => $department->id]) }}">
                {{ csrf_field() }}

                <input name="id" type="hidden" value="{{ $department->id }}" />
                <div class="input-area{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>

                    <input id="name" type="text"  name="name" autocomplete="off" value="{{ empty(old('name')) ? $department->name : old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                    @endif
                </div>

                <div class="input-area {{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description">Remarks</label>

                    <input id="description" type="text" name="description" value="{{empty(old('description')) ? $department->description : old('description') }}" />
                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>

                 <div class="button-area">
			    <a href="{{ route('departments') }}" class="big-btn">Back</a>
                            <button type="submit" class="sbtn">
                                Update
                            </button>
                </div>
            </form>
       
@endsection
@extends('layouts.app')

@section('title','Add Project')
@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
@endsection
@section('content')


    <div class="wrapper">
        <h1>Add Project</h1>

        <div class="section content-area">
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

                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-right">
                        <input type="submit" class="sbtn" value="Create New Project">
                        <a href="{{ route('projects.index') }}" class="big-btn">Back</a>
                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection
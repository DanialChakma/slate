@extends('layouts.app')


@section('title','Edit Project')

@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
@endsection
@section('content')

    <div class="wrapper">

        <div class="section content-area">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('projects.update', ['id' => $project->id]) }}">
                {{ csrf_field() }}

                <input name="id" type="hidden" value="{{ $project->id }}" />

                <div class="form-group">
                    <div class="input-area">
                        <label for="select">Department <span class="required">*</span></label>
                        <select name="department_id" id="department">
                            @foreach(\App\Department::all() as $department)
                                @if( $project->department->id  == $department->id )
                                    <option selected value="{{$department->id}}">{{$department->name}}</option>
                                @else
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-area{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>
                    <input id="name" type="text" name="name" value="{{ empty(old('name')) ? $project->name : old('name') }}" required autofocus>

                </div>

                @if ($errors->has('name'))
                    <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                @endif

                <div class="input-area{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description">Remarks</label>

                    <textarea id="description"  name="description">{{ empty(old('description')) ? $project->description : old('description') }}</textarea>


                </div>

                @if ($errors->has('description'))
                    <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                @endif

                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6 text-right">
                        <input type="submit" class="sbtn" value="Update">
                        <a href="{{ route('projects.index') }}" class="big-btn">Back</a>
                    </div>
                </div>

            </form>
        </div>
    </div>


@endsection

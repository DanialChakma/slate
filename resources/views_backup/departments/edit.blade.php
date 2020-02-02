@extends('layouts.app')

@section('title','Edit Department')

@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
@endsection

@section('content')
    <div class="container">
    <div class="row">
        <div class="col-xs-12">
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

                <div class="input-area">
                    	<div class="col-md-6"></div>
                        <div class="col-md-6 text-right">
			    <a href="{{ route('departments.index') }}" class="big-btn">Back</a>
                            <button type="submit" class="sbtn">
                                Update
                            </button>
                            
                        </div>
                    
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
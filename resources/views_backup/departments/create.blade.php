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
    <div class="container">
    <div class="row">
        <div class="col-xs-12">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('departments.store') }}">
                {{ csrf_field() }}

                <div class="input-area{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>

                    <input id="name" type="text"  name="name" autocomplete="off" value="{{ old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                    @endif
                </div>

                <div class="input-area {{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description">Remarks</label>

                    <input id="description" type="text" name="description" value="{{ old('description') }}" />
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
                            Create new department
                        </button>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
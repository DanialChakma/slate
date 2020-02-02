@extends('layouts.app')

@section('title','Edit Industry')

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
            <form class="form-horizontal" role="form" method="POST" action="{{ route('industries.update', ['id' => $industry->id]) }}">
                {{ csrf_field() }}

                <input name="id" type="hidden" value="{{ $industry->id }}" />

                <div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Industry Name <span class="required">*</span></label>
                    <input id="name" type="text"  name="name" autocomplete="off" value="{{ empty(old('name')) ? $industry->name : old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-right">
			    <a href="{{ route('industries.index') }}" class="big-btn">Back</a>
                            <button type="submit" class="sbtn">
                                Save it
                            </button>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
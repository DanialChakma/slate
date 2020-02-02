{{--@extends('layouts.app')--}}

{{--@section('content')--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<div class="col-md-8 col-md-offset-2">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-lg-12">--}}
                                {{--<img src="{{asset('images/logo-slate.png')}}" alt="Slate" class="center-block" style="margin-bottom: 2.5rem;" />--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<form class="form-horizontal" method="POST" action="{{ route('login') }}">--}}
                            {{--{{ csrf_field() }}--}}

                            {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
                                {{--<label for="email" class="col-md-4 control-label">E-Mail Address</label>--}}

                                {{--<div class="col-md-6">--}}
                                    {{--<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required autofocus>--}}

                                    {{--@if ($errors->has('email'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">--}}
                                {{--<label for="password" class="col-md-4 control-label">Password</label>--}}

                                {{--<div class="col-md-6">--}}
                                    {{--<input id="password" type="password" class="form-control" name="password" placeholder="Password" required>--}}

                                    {{--@if ($errors->has('password'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group">--}}
                                {{--<div class="col-md-6 col-md-offset-4">--}}
                                    {{--<div class="checkbox">--}}
                                        {{--<label>--}}
                                            {{--<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group">--}}
                                {{--<div class="col-md-8 col-md-offset-4">--}}
                                    {{--<button type="submit">--}}
                                        {{--Login--}}
                                    {{--</button>--}}

                                    {{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
                                        {{--Forgot Your Password?--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-lg-12">--}}
                                {{--<img src="{{asset('images/logo-quann.png')}}" alt="Quann" class="center-block" />--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--@endsection--}}


@extends('layouts.login')
@section('login')
    <div class="login-area">
        <div class="login-box">
            <div class="logo-s"><img src="{{asset('images/logo-slate.png')}}" alt=""/></div>
            <div class="login-panel">
                <form method="POST" action="{{route('login')}}">
                    {{ csrf_field() }}
                    <input type="text" name="email" value="{{ old('email') }}" placeholder="Enter Username" class="icon-icon-left icon-user">
             
                    <input type="password" name="password" placeholder="Password"  class="icon-icon-left icon-key">
                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                        <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                    <input type="submit" value="Login"></form>
                     <a class="btn btn-link" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
            </div>
            <div class="logo-s"><img src="{{ asset('images/logo-quann.png') }}" alt=""/></div>
        </div>
    </div>
@endsection
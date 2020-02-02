@extends('layouts.app')


@section('content')

    <div class="wrapper">

        <h1>Change Account Password</h1>
        <div class="clearfix"></div>


        <div class="section content-area">


            <form class="form-horizontal" role="form" method="POST" action="{{ route('users.userChangePassword', [ 'id' => auth()->user()->id ])}}">
                {{ csrf_field() }}



                <div class="input-area{{ $errors->has('password') ? ' has-error' : '' }}">

                    <label for="current_password">Current Password</label>

                    <input id="current_password" type="password" class="form-control" name="current_password" autocomplete="off" value="" required autofocus>

                    @if ($errors->has('current_password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('current_password') }}</strong>
                            <br>
                        </span>
                        <br>
                    @endif
                </div>

                <div class="input-area{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">New Password</label>

                    <input id="password" type="password" class="form-control" name="password" autocomplete="off" value="" required autofocus>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="input-area{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password">Confirm Password</label>

                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="off" value="" required autofocus>

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>


                <div class="clearfix"></div>

                <input type="submit" class="fr sbtn" value="Update My Password">

            </form>

        </div>




    </div>


@endsection

@extends('layouts.app')

@section('title','Edit User')
@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
@endsection
@section('content')

    <div class="wrapper">
            <h1>Edit User</h1>


        <div class="section content-area">

                <form class="form-horizontal" role="form" method="POST" action="{{ route('users.update',['id' => $user->id]) }}">
                    {{ csrf_field() }}

                    <div class="input-area{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name">Name <span class="required">*</span></label>

                        <input id="name" type="text"  name="name" autocomplete="off" value="{{ empty(old('name')) ? $user->name : old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                        @endif
                    </div>


                    <div class="input-area">
                        <label for="role_id">Role <span class="required">*</span></label>
                        <select id="role_id"  name="role_id">
                            <option value="">Select one</option>
                            {{--{{ empty($user) ? "" : $user }}--}}
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{$role->id == $user->role->id ? "selected" : ""}}>{{ $role->name }}</option>

                            @endforeach

                        </select>
                    </div>
                    {{--

                                    <input type="hidden" id="selected_role" name="selected_role">

                                    {{'<script> var role_id = $("#role_id").val();</script>'}}
                    --}}

                    <div class="input-area">
                        <label for="department_id">Department <span class="required">*</span></label>
                        <select id="department_id"  name="department_id">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{$department->id == $user->department->id ? "selected" : ""}}>{{ $department->name }}</option>
                            @endforeach

                        </select>
                    </div>


                    <div id="reporting_person_block" class="input-area">
                        <label for="supervisor_id">Reporting Person <span class="required">*</span></label>
                        <select id="supervisor_id"  name="supervisor_id">
                            <option value="">Select one</option>
                            @foreach($supervisors as $supervisor)
                                {{--{{ empty($user->supervisor) ? "" : $user->supervisor }}--}}
                                <option value="{{ $supervisor->id }}" {{$supervisor->id == empty($user->supervisor) ? "" : $user->supervisor->id ? "selected" : ""}}>{{ $supervisor->name }} ({{ $supervisor->role->name }})</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="input-area {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">Email <span class="required">*</span></label>

                        <input id="email" type="email"  name="email" value="{{ empty(old('email')) ? $user->email : old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        {{--@else
                            <span class="info">
                            <small>* Email address must be unique</small>
                            </span>
                            --}}

                        @endif
                    </div>

                    <div class="input-area {{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label for="phone">Phone <span class="required">*</span></label>

                        <input id="phone" type="text"  name="phone" value="{{ empty(old('phone')) ? $user->phone : old('phone') }}" required autofocus>

                        @if ($errors->has('phone'))
                            <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                        {{--@else
                            <span class="info">
                            <small>* Phone number must be unique</small>--}}{{--
                        </span>--}}
                        @endif
                    </div>

                    <div></div>


                    <div class="clearfix"></div>
                    <div class="row text-right input-area">
			<a href="{{ route('users.index') }}" class="big-btn">Back</a>
                        <button type="submit">Update User</button>
                    </div>
                </form>
        </div>


        <h1>Change User Password</h1>
        <div class="clearfix"></div>


        <div class="section content-area">



            <form class="form-horizontal" role="form" method="POST" action="{{ route('users.updatePassword',['id' => $user->id]) }}">
                {{ csrf_field() }}

                <div class="input-area{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">New Password</label>

                    <input id="password" type="password" class="form-control" name="password" autocomplete="off" value="" required autofocus>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        <br>
                    @endif
                </div>

                <div class="input-area{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password_confirmation">Confirm Password</label>

                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="off" value="" required autofocus>

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>


                <div class="clearfix"></div>

                <input type="submit" class="fr sbtn" value="Update User Password">

            </form>

        </div>




    </div>


@endsection

@section('FooterAdditionalCodes')
    <script type="text/javascript">
        $(function () {
            if($('#role_id').val() == 1){
                $('#supervisor_id').val("");
                $("#supervisor_id").prop('required',false);
                $('#reporting_person_block').hide();
            }else{
                $("#supervisor_id").prop('required',true);
            }

            $('#role_id').change(function() {
                $('#supervisor_id').val("");
                if($(this).val() == 1){
                    $("#supervisor_id").prop('required',false);
                    $('#reporting_person_block').hide();
                }else{
                    $("#supervisor_id").prop('required',true);
                    $('#reporting_person_block').show();
                }
            });
        });
    </script>
@endsection

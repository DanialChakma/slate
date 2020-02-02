@extends('layouts.app')

@section('title','Add User')
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

            <form class="form-horizontal" role="form" method="POST" action="{{ route('users.store') }}">
                {{ csrf_field() }}

                <div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>
                    <input id="name" type="text"  name="name" autocomplete="off" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="input-area {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">Password <span class="required">*</span></label>
                    <input id="password" type="password"  name="password" autocomplete="off" value="" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="input-area">
                    <label for="role_id">Role <span class="required">*</span></label>
                    <select id="role_id"  name="role_id" required>
                        <option value="">Select one</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $role->id == 4 ? "selected" : "" }}  @if(old('role_id') == $role->id) {{ 'selected' }} @endif >{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="input-area">
                    <label for="department_id">Department <span class="required">*</span></label>
                    <select id="department_id"  name="department_id">
                        {{--{{ $role_id = '<script>role_id</script>' }}--}}
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @if(old('department_id') == $department->id) {{ 'selected' }} @endif >{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div id="reporting_person_block" class="input-area">
                    <label for="supervisor_id">Reporting Person <span class="required">*</span></label>
                    <select id="supervisor_id"  name="supervisor_id">
                        {{--{{ $role_id = '<script>role_id</script>' }}--}}

                        <option value="">Select one</option>
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" @if(old('supervisor_id') == $supervisor->id) {{ 'selected' }} @endif>{{ $supervisor->name }} ({{ $supervisor->role->name }})</option>
                        @endforeach
                    </select>
                </div>


                <div class="input-area {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">Email <span class="required">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="input-area {{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="phone">Phone <span class="required">*</span></label>
                    <input id="phone" type="text"  name="phone" value="{{ old('phone') }}" required>
                    @if ($errors->has('phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6 text-right">
			<a href="{{ route('users.index') }}" class="big-btn">Back</a>
                        <input type="submit" class="sbtn" value="Create New User">
                    </div>
                </div>
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

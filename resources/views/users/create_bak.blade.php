@extends('layouts.app')

{{--@section('title','Add User')--}}

@section('content')

    <div class="wrapper">
        <h1>Add User</h1>

        <div class="section content-area">

            <form class="form-horizontal" role="form" method="POST" action="{{ route('users.store') }}">
                {{ csrf_field() }}

                <div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name</label>
                    <input id="name" type="text"  name="name" autocomplete="off" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="input-area {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">Password</label>
                    <input id="password" type="password"  name="password" autocomplete="off" value="" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="input-area">
                    <label for="role_id">Role</label>
                    <select id="role_id"  name="role_id">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $role->id == 4 ? "selected" : "" }}  @if(old('role_id') == $role->id) {{ 'selected' }} @endif >{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="input-area">
                    <label for="department_id">Department</label>
                    <select id="department_id"  name="department_id">
                        {{--{{ $role_id = '<script>role_id</script>' }}--}}
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @if(old('department_id') == $department->id) {{ 'selected' }} @endif >{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="input-area">
                    <label for="supervisor_id">Reporting Person</label>
                    <select id="supervisor_id"  name="supervisor_id">
                        {{--{{ $role_id = '<script>role_id</script>' }}--}}
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" @if(old('supervisor_id') == $supervisor->id) {{ 'selected' }} @endif>{{ $supervisor->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="input-area {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="input-area {{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text"  name="phone" value="{{ old('phone') }}" required>
                    @if ($errors->has('phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="clearfix"></div>
                <input type="submit" class="fr sbtn" value="Create New User">
            </form>
        </div>
    </div>

@endsection


{{--
@section('FooterAdditionalCodes')
    <script src="{{ url('js/vendor/tinymce/js/tinymce/tinymce.min.js') }}">
    </script>
    <script>
        tinymce.init({
            selector: 'textarea#details',
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            },
            height: 300,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
            image_advtab: true,
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ],
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = '/' + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        });
    </script>
@endsection--}}

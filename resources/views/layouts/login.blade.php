<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title','Slate') : {{ config('app.company_name') }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    {{--<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/bootstrap-theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.mmenu.all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-confirm.min.css') }}" rel="stylesheet">

    <script src="{{ asset('js/jquery-1.10.2.min.js')}}" type="text/javascript"></script>

    <!-- CSRF Token -->

    <script>
        var base_url = "{!! URL::to('/') !!}/";
        {{--window.Laravel ={!! json_encode([--}}
        {{--'csrfToken' => csrf_token(),--}}
        {{--]) !!};--}}
    </script>
    @yield('login')
    @yield('HeaderAdditionalCodes')
</head>
<body>












@yield('FooterAdditionalCodes')
</body>
</html>

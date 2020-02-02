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
    <script src="{{ asset('js/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datepicker.en.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.mmenu.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery-confirm.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- CSRF Token -->

    <script>
        var base_url = "{!! URL::to('/') !!}/";
        {{--window.Laravel ={!! json_encode([--}}
            {{--'csrfToken' => csrf_token(),--}}
        {{--]) !!};--}}
    </script>
<style>
    .input-group{
        z-index: 0;
    }
</style>
    @yield('HeaderAdditionalCodes')
</head>
<body>


<div class="section top-area">
    <div class="wrapper">
        @if (Auth::check())
        <div class="mobile-menu sqmenu"><a href="#menu"><img src="{{asset('images/menu-button.svg')}}" alt=""/></a></div>
        <div class="mobile-menu">
            <nav id="menu">
                <ul>
                    <li><a href="{{ route('home') }}">Schedule</a></li>
                    @can('viewList', \App\Meeting::class)
                        <li><a href="{{ route('meetings') }}">Meetings</a></li>
                    @endcan

                    @can('viewList', \App\ClientCompany::class)
                        <li><a href="{{ route('clientCompanies') }}">Client Management</a></li>
                    @endcan

                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                        <li><a href="#">Survey</a>
                             <ul>
                                @if(auth()->user()->isAdmin())
                                    <li><a href="{{route('questions')}}">Questions</a></li>
                                    <li><a href="{{route('surveys')}}">Survey</a></li>
                                @endif
                                 <li><a href="{{route('surveys.report')}}">Reports</a></li>
                             </ul>
                        </li>
                    @endif

                    @can('viewList', \App\User::class)
                    <li><a href="{{ route('users') }}">Manage</a>
                        <ul>
                            @can('viewList', \App\Industry::class)
                                <li><a href="{{ route('industries') }}">Industry</a></li>
                            @endcan
                            @can('viewList', \App\Department::class)
                                <li><a href="{{ route('departments') }}">Departments</a></li>
                            @endcan
                            @can('viewList', \App\Project::class)
                                <li><a href="{{ route('projects') }}">Project Type</a></li>
                            @endcan
                            @can('viewList', \App\User::class)
                                <li><a href="{{ route('users') }}">Users</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                </ul>
            </nav>
        </div>
        @endif
            <div class="logo-area">
                <a href="{{ route('home') }}" style="display: block; text-decoration: none;">
                <span class="slate-logo">
                    <img src="{{asset('images/logo-slate.png')}}" width="77" height="31" alt="slate logo"/>
                </span>
                    <span class="quann-logo">
                    <img src="{{asset('images/logo-quann.png')}}" width="96" height="28" alt="quann logo"/>
                </span>
                </a>
            </div>


        @if (Auth::check())
            <div class="main-menu nav">
                <ul id="menu_id">
                <li><a href="{{ route('home') }}">Schedule</a></li>
                <li><a href="{{route('meetings.listAccount')}}">Meetings</a>
		</li>
                @can('viewList', \App\ClientCompany::class)
                    <li><a href="{{ route('clientCompanies') }}">Client Management</a></li>
                @endcan
                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                        <li><a href="#">Survey</a>
                             <ul>
                                @if(auth()->user()->isAdmin())
                                    <li><a href="{{route('questions')}}">Questions</a></li>
                                    <li><a href="{{route('surveys')}}">Survey</a></li>
                                @endif
                                 <li><a href="{{route('surveys.report')}}">Reports</a></li>
                             </ul>
                        </li>
                    @endif

                @can('viewList', \App\User::class)
                    <li><a href="{{ route('users') }}">Manage</a>
                        <ul>
                            @can('viewList', \App\Industry::class)
                            <li><a href="{{ route('industries') }}">Industry</a></li>
                            @endcan
                            @can('viewList', \App\Department::class)
                                <li><a href="{{ route('departments') }}">Departments</a></li>
                            @endcan
                                @can('viewList', \App\Project::class)
                                    <li><a href="{{ route('projects') }}">Project Type</a></li>
                                @endcan
                            @can('viewList', \App\User::class)
                                <li><a href="{{ route('users') }}">Users</a></li>
                            @endcan
			
			            </ul>
                    </li>
                @endcan
            </ul>
            </div>


            <div class="user-area">
                <div class="nav">
                    <ul>
                       {{-- <li><a href="#"><img src="{{asset('images/icon-notification.svg')}}" alt=""/>

                                <div class="notify-number">42</div>
                            </a></li>
                        <li><a href="#"><img src="{{asset('images/icon-exit.svg')}}" alt=""/></a></li> --}}
                        <li class="avatar"><a href="#"><span>A</span></a>
                            <ul>
                                <li><a href="{{ route('users.userChangePasswordView', [ 'id' => auth()->user()->id ]) }}">Change Password</a></li>
                              <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                  Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  {{ csrf_field() }}
                                </form>
                              </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            </div>
        @endif


</div>

<main class="section">

    <div class="wrapper">
        <h1>@yield('h1Text','')</h1>

        @if( Session::has('pending_sms_msg') )
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert-box" id="alert_box" style="display: none;">
                        <div class="alert alert-info text-center" role="alert">

                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                @if(Session::has('message'))
                    @if(Session::get('status') == true)
                        <div class="alert-box">
                            <div class="alert alert-success" role="alert">
                                <strong>{!! Session::get('message') !!}</strong>
                            </div>
                        </div>
                    @endif
                    @if(Session::get('status') == false)
                        <div class="alert-box">
                            <div class="alert alert-danger" role="alert">
                                <strong>{!! Session::get('message') !!}</strong>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>


        <div class="section content-area">
            @yield('content')
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-12">
                <hr/>
                <h5 style="text-align: center;"><strong>Copyright &copy; {{ date('Y') }}, {{ config('app.copy_right_name') }}</strong></h5>
            </div>
        </div>
    </div>


    @if( ! session()->has('notification_box_enabled') )
        <div style="display: none;" class="meeting-alert">
            <h5>Meeting Alert</h5>
            <div id="meetings" class="meeting-content-area mCustomScrollbar" data-mcs-theme="rounded-dark">

            </div>
            <div class="smbtn m-btnarea"><a id="notification_ok" href="#">OK</a></div>
        </div>
    @endif

    @if( session()->has('notification_box_enabled') )
       <input type="hidden" value="{{session()->get('notification_box_enabled')}}" id="noti_is_enabled">
    @else
        <input type="hidden" value="false" id="noti_is_enabled">
    @endif


</main>

<script type="application/javascript">
 $(function(){
     setNavigation();
//
//     $(".mCustomScrollbar").mCustomScrollbar({
//         axis:"yx", // vertical and horizontal scrollbar
//         scrollButtons: {
//             enable: true
//         },
//         advanced:{
//
//             updateOnContentResize:true,
//             updateOnBrowserResize:true
//         }
//     });


    var dp = $('.only-time').datepicker({
        onlyTimepicker: true,
        timepicker: true
    }).data('datepicker');

    $("#menu").mmenu({
        navbars: [{
            height: 1,
            content: [
	            '<div class="mob-icon"><img src="http://45.249.100.46/images/logo-slate.png" /></div>',
            ]
        }, true],
        "extensions": [
	        "pagedim-black", "listview-huge", "fx-panels-slide-100", "fx-listitems-slide", "fx-menu-slide", "border-full"
        ]
    });


  /*  $('.nav #menu_id>li').hover(function () {
        $(this).addClass('current-menu-item');     }, function () {
        $(this).removeClass('current-menu-item');     });  */





     var is_enabled = $("#noti_is_enabled").val();
     var path = window.location.pathname;
     if( ! path.includes('login') && is_enabled == "false" ){

         $.ajax({
             type: "GET",
             url: "{{route('meetings.getUpcomingMeetings')}}",
             async: false,
             dataType: 'JSON',
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             success: function (rows) {
                 var meetings_html = "";
                 for(var index in rows ){
                     var url = "{{ route('meetings.changeStatus',['id'=>''])}}"+'/'+rows[index].id;

                     meetings_html += '<div class="single-meeting">'+
                             '<h4 style="margin: 5px 0px;"><a href="'+url+'">'+rows[index].eventName+'</a></h4>'+
                             'Date:'+rows[index].date+'<br/>'+
                             'From:'+rows[index].time.split('-')[0]+' to '+rows[index].time.split('-')[1]+
                             '<br/>With <strong>'+rows[index].client+'</strong>'+
                             '</div>';
                 }

                 if( rows.length > 0 ){
                     $("div.meeting-alert").fadeIn(500);
                     $("#meetings").html(meetings_html);
                     if($(".mCustomScrollbar").length){
                         $(".mCustomScrollbar").mCustomScrollbar("update");
                     }else{
                         setTimeout(function(){
                             $(".mCustomScrollbar").mCustomScrollbar("update");
                         },1000)
                     }
                 }

             },
             error: function (jqXHR, textStatus, errorThrown) { },
             processData: false,
         });

     }


     $(document).on('click','a#notification_ok',function(event){
         event.preventDefault();
         $(this).closest('div.meeting-alert').hide();
         $.ajax({
             type: "GET",
             url: "{{route('meetings.toggleNotification')}}",
             async: false,
             dataType: 'JSON',
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             success: function (response) {
                 if( response.status ){
                     // JconfirmAlert('Notification Box Diactivation.',response.message);
                 }
             },
             error: function (jqXHR, textStatus, errorThrown) { },
             processData: false
         });
     })

 });


function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $(".nav a").each(function () {
        var href = $(this).attr('href');
        if (base_url + path.replace('/','') === href) {
            $(this).closest('li').addClass('current-menu-item');
        }
    });
}
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var pending_message = "{{Session::get('pending_sms_msg')}}";
        var length = $('#alert_box').length;
        if( length > 0 ){
            $.ajax({
                type: "GET",
                url: "{{route('SMSGW.checkBalance')}}",
                async: false,
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {

                    if( response.HasPending == true ){
                        if( response.status == "OK" ){
                            var html = '<div class="alert alert-info text-center" role="alert">'+
                                    '<strong>'+'Refill successful. Would you like to send the pending SMSes now?'
                                    +'</strong>'+
                                    '<br/><button id="send" class="btn-primary">Yes</button>'+
                                    '</div>';
                            $('#alert_box').html(html);

                        }else{
                            var html = '<div class="alert alert-info text-center" role="alert">'+
                                    '<strong>'+pending_message
                                    +'</strong>'+

                                    '</div>';
                            $('#alert_box').html(html);
                        }
                        $('#alert_box').show();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) { },
                processData: false
            });
        }


        $(document).on('click','button#send',function(){
            $.ajax({
                type: "GET",
                url: "{{ route('SMSGW.sendPendingSmses') }}",
                async: false,
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {

                },
                error: function (jqXHR, textStatus, errorThrown) { },
                processData: false
            });
        });

        $('.alert-box').fadeOut(3000);

    });
</script>


<script type="text/javascript"src="{{asset('js/ourjs.js')}}"></script>

@yield('FooterAdditionalCodes')
</body>
</html>

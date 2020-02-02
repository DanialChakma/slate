<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>


    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
          <title> @yield('title','Slate') : {{ config('app.company_name') }}</title>


          <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
          <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
            <link href="{{ asset('css/bootstrap-theme.min.css') }}" rel="stylesheet">
              <link href="{{ asset('css/reset.css') }}" rel="stylesheet">
                <link href="{{ asset('css/base.css') }}" rel="stylesheet">
                  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
                    <script src="https:=//code.jquery.com/jquery-2.1.4.min.js"></script>
                    <link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
                      <script src="{{ asset('js/datepicker.min.js') }}"></script>
                      <script src="{{ asset('js/datepicker.en.js') }}"></script>
                      <script src="{{ asset('js/jquery.mmenu.all.js') }}" type="text/javascript"></script>
                      <script src="{{ asset('js/jquery-confirm.min.js') }}" type="text/javascript"></script>
                      <link href="{{ asset('css/jquery.mmenu.all.css') }}" rel="stylesheet">
                        <link href="{{ asset('css/jquery-confirm.min.css') }}" rel="stylesheet">




                          <!-- CSRF Token -->

                          <script>
                            window.Laravel ={!! json_encode([
                            'csrfToken' => csrf_token(),
                            ]) !!};
                          </script>


                        </head>
  <body>


    <div class="section top-area">
      <div class="wrapper">
        @if (Auth::check())
        <div class="mobile-menu sqmenu">
          <a href="#menu">
            <img src="{{asset('images/menu-button.svg')}}" alt=""/>
          </a>
        </div>



        <div class="mobile-menu">
          <nav id="menu">
            <ul>
              <li>
                <a href="{{route('schedules.create')}}">Schedule</a>
              </li>
              <li>
                <a href="#">Meeting Status</a>
                <ul>
                  <li>
                    <a href="{{ route('projects.index') }}">Projects</a>
                  </li>
                  <li>
                    <a href="{{ route('meetings') }}">Meeting Schedule</a>
                  </li>
                  <li>
                    <a href="#">sub menu</a>
                  </li>
                  <li>
                    <a href="#">sub menu</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#">Client Management</a>
                <ul>
                  <li>
                    <a href="{{route('industries')}}">Industry</a>
                  </li>
                  <li>
                    <a href="{{route('clientCompanies')}}">Client Company</a>
                  </li>
                  <li>
                    <a href="#">sub menu</a>
                  </li>
                </ul>
              </li>
              <li><a href="{{route('surveys')}}">Surveys</a></li>
              <li>
                <a href="{{ route('users.index') }}">User Management </a>
              </li>
            </ul>
          </nav>
        </div>
        @endif
        <div class="logo-area">
          <span class="slate-logo">
            <img src="{{asset('images/logo-slate.png')}}"  width="77" height="31"
                 alt="slate logo"/>
          </span>
          <span class="quann-logo">
            <img
                        src="{{asset('images/logo-quann.png')}}" width="96" height="28" alt="quann logo"/>
          </span>
        </div>


        @if (Auth::check())
        <div class="main-menu nav">
          <ul id="menu_id">
            <li>
              <a href="{{route('schedules.create')}}">Schedule</a>
            </li>
            <li>
              <a href="#">Meeting Status</a>
              <ul>
                @can('viewList', \App\Project::class)
                <li>
                  <a href="{{ route('projects.index') }}">Projects</a>
                </li>
                @endcan

                {{-- <li>
                  <a href="{{ route('projects.index') }}">Projects</a>
                </li>--}}
                <li>
                  <a href="{{ route('meetings') }}">Meeting Schedule</a>
                </li>
                <li>
                  <a href="#">sub menu</a>
                </li>
                <li>
                  <a href="#">sub menu</a>
                </li>
              </ul>
            </li>
            <li>
              <a href="#">Client Management</a>
              <ul>
                <li>
                  <a href="{{route('industries')}}">Industry</a>
                </li>
                <li>
                  <a href="{{route('clientCompanies')}}">Client Company</a>
                </li>
                <li>
                  <a href="#">sub menu</a>
                </li>
              </ul>
            </li>
            
            <li><a href="{{route('surveys')}}">Surveys</a></li>
            
            <li>
              <a href="{{ route('users.index') }}">User Management </a>
            </li>
          </ul>
        </div>


        <div class="user-area">
          <div class="nav">
            <ul>
              <li>
                <a href="#">
                  <img src="{{asset('images/icon-notification.svg')}}" alt=""/>

                  <div class="notify-number">42</div>
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="{{asset('images/icon-exit.svg')}}" alt=""/>
                </a>
              </li>
              <li class="avatar">
                <a href="#">
                  <span>A</span>
                </a>
                <ul>
                  <li>
                    <a href="#">Setting</a>
                  </li>
                  <li>
                    <li><a href="{{route('users.user_change_password')}}">Change Password</a></li>
                  </li>
                  <li>
                    <a href="{{ route('logout') }}">Logout</a>
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
            <h5 style="text-align: center;">Copyright (c) {{ date('Y') }}, {{ config('app.company_name') }}</h5>
          </div>
        </div>
      </div>
    </main>


    <script src="{{ asset('js/custom.js') }}"></script>


  </body>
</html>

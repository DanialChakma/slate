<div class="section top-area">
    <div class="wrapper">
        @if (Auth::check())
            <div class="mobile-menu sqmenu">
                <a href="#menu"><img src="{{asset('images/menu-button.svg')}}" alt=""/></a>
            </div>

            <div class="mobile-menu">
                <nav id="menu">
                    <ul>
                        <li><a href="{{ route('meetings.create') }}">Schedule</a></li>
                        @can('viewList', \App\Meeting::class)
                            <li> <a href="#">Meetings</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('meetings') }} ">Meeting Status</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('meetings.meetingsOfFieldStuffs') }} ">Stuff Meetings</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @if(auth()->user()->isAdmin())
                            <li><a href="#">Company</a>
                                <ul>
                                    @can('viewList', \App\ClientCompany::class)
                                        <li><a href="{{ route('clientCompanies') }}">Client Company</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endif

                        @can('viewList', \App\Survey::class)
                            <li><a href="{{ route('surveys') }}">Survey</a></li>
                        @endcan

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
                                        <li><a href="{{ route('users') }}">User</a></li>
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
                    <li><a href="{{ route('meetings.create') }}">Schedule</a></li>
                    @can('viewList', \App\Meeting::class)
                        <li> <a href="#">Meetings</a>
                            <ul>
                                <li>
                                    <a href="{{ route('meetings') }} ">Meeting Status</a>
                                </li>
                                <li>
                                    <a href="{{ route('meetings.meetingsOfFieldStuffs') }} ">Stuff Meetings</a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @if(auth()->user()->isAdmin())
                        <li><a href="#">Company</a>
                            <ul>
                                @can('viewList', \App\ClientCompany::class)
                                    <li><a href="{{ route('clientCompanies') }}">Client Company</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @can('viewList', \App\Survey::class)
                        <li><a href="{{ route('surveys') }}">Survey</a></li>
                    @endcan

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
                                    <li><a href="{{ route('users') }}">User</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                </ul>
            </div>


            <div class="user-area">
                <div class="nav">
                    <ul>
                        <li><a href="#"><img src="{{asset('images/icon-notification.svg')}}" alt=""/>

                                <div class="notify-number">42</div>
                            </a></li>
                        <li><a href="#"><img src="{{asset('images/icon-exit.svg')}}" alt=""/></a></li>
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
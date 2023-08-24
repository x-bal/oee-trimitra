<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} | {{ $title }}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="{{ asset('/') }}css/vendor.min.css" rel="stylesheet" />
    <link href="{{ asset('/') }}css/google/app.min.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->

    @stack('style')
</head>

<body>
    <!-- BEGIN #loader -->
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <!-- END #loader -->
    <!-- BEGIN #app -->
    <div id="app" class="app app-header-fixed app-sidebar-fixed app-with-wide-sidebar app-with-light-sidebar">
        <!-- BEGIN #header -->
        <div id="header" class="app-header">
            <!-- BEGIN navbar-header -->
            <div class="navbar-header">
                <button type="button" class="navbar-desktop-toggler" data-toggle="app-sidebar-minify">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/dashboard" class="navbar-brand">
                    <b class="me-1">Kelola</b> Biz
                </a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- END navbar-header -->
            <!-- BEGIN header-nav -->
            <div class="navbar-nav">
                <div class="navbar-item navbar-form"></div>

                <div class="navbar-item navbar-user dropdown">
                    @auth
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <div class="image image-icon bg-gray-800 text-gray-600">
                            <i class="fa fa-user"></i>
                        </div>
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span> <b class="caret ms-lg-2"></b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-1">
                        <a href="javascript:;" class="dropdown-item">Edit Profile</a>
                        <a href="javascript:;" class="dropdown-item"><span class="badge badge-danger float-end">2</span> Inbox</a>
                        <a href="javascript:;" class="dropdown-item">Calendar</a>
                        <a href="javascript:;" class="dropdown-item">Setting</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">Log Out</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    @else
                    <a href="#modal-login" class="btn btn-primary" data-bs-toggle="modal"><i class="fas fa-sign-in-alt"></i> Login</a>
                    @endauth
                </div>
            </div>
            <!-- END header-nav -->
        </div>
        <!-- END #header -->
        <!-- BEGIN #sidebar -->
        <div id="sidebar" class="app-sidebar">
            <!-- BEGIN scrollbar -->
            <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
                <!-- BEGIN menu -->
                <div class="menu">
                    @auth
                    <div class="menu-profile">
                        <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
                            <div class="menu-profile-cover with-shadow"></div>
                            <div class="menu-profile-image menu-profile-image-icon bg-gray-900 text-gray-600">
                                <i class="fa fa-user fs-48px mb-n4"></i>
                            </div>
                            <div class="menu-profile-info">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="menu-caret ms-auto"></div>
                                </div>
                                <!-- <small>Front end developer</small> -->
                            </div>
                        </a>
                    </div>
                    <div id="appSidebarProfileMenu" class="collapse">
                        <div class="menu-item pt-5px">
                            <a href="javascript:;" class="menu-link">
                                <div class="menu-icon"><i class="fa fa-cog"></i></div>
                                <div class="menu-text">Settings</div>
                            </a>
                        </div>
                        <div class="menu-divider m-0"></div>
                    </div>
                    @endauth
                    <div class="menu-header">Navigation</div>
                    <div class="menu-item active">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <div class="menu-icon">
                                <i class="material-icons">home</i>
                            </div>
                            <div class="menu-text">Dashboard</div>
                        </a>
                    </div>

                    @auth
                    <div class="menu-item has-sub">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="menu-text">Data Master</div>
                            <div class="menu-caret"></div>
                        </a>

                        <div class="menu-submenu">
                            <div class="menu-item">
                                <a href="{{ route('lines.index') }}" class="menu-link">
                                    <div class="menu-text">Line Process</div>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('machines.index') }}" class="menu-link">
                                    <div class="menu-text">Machines</div>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('products.index') }}" class="menu-link">
                                    <div class="menu-text">Products</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('users.index') }}" class="menu-link">
                            <div class="menu-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="menu-text">User Management</div>
                        </a>
                    </div>

                    <div class="menu-item has-sub">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="menu-text">Setting</div>
                            <div class="menu-caret"></div>
                        </a>

                        <div class="menu-submenu">
                            <div class="menu-item">
                                <a href="{{ route('brokers.index') }}" class="menu-link">
                                    <div class="menu-text">Broker</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    @endauth
                </div>
            </div>
        </div>
        <div class="app-sidebar-bg"></div>
        <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>


        <div id="content" class="app-content">

            @auth
            <ol class="breadcrumb float-xl-end">
                @foreach($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item"><a href="javascript:;">{{ $breadcrumb }}</a></li>
                @endforeach
            </ol>
            @endauth
            <h1 class="page-header">{{ $title }}</h1>

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach($errors->all() as $error)
                {{ $error }} <br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></span>
            </div>
            @endif

            @yield('content')
        </div>

        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
    </div>

    <!-- ================== BEGIN core-js ================== -->
    <script src="{{ asset('/') }}js/vendor.min.js"></script>
    <script src="{{ asset('/') }}js/app.min.js"></script>
    <script src="{{ asset('/') }}js/theme/google.min.js"></script>
    <!-- ================== END core-js ================== -->

    @stack('script')
</body>

</html>
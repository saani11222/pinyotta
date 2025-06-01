<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/admin/dashboard') }}" style="color:white;">PiperPlus Admin</a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>


        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> Settings <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li class="divider"></li>
                    <li><a href="{{ url('/admin/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>

        <style>
            .example {
            /* height: 570px;
            overflow-y: scroll;  */
            max-height: 570px!important;
            overflow: auto;
            /* Add the ability to scroll */
            }

            /* Hide scrollbar for Chrome, Safari and Opera */
            .example::-webkit-scrollbar {
                display: none;
            }

            /* Hide scrollbar for IE, Edge and Firefox */
            .example {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
            }
        </style>

        <!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                    <div class="example">
                    <ul class="nav" id="side-menu">
                        <!-- <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                            </div>
                        </li> -->
                        <li>
                            <a href="{{ url('/admin/dashboard') }}" class="active"><i class="fa fa-home fa-fw"></i> Dashboard</a>
                        </li>
                        <!-- && $module->name !== 'Roles' && $module->name !== 'Admin Users' -->
                        <!-- if (strpos($page->page_route, 'edit') === false && strpos($page->page_route, 'delete') === false) -->

                        @foreach ($modules as $module)
                            @if ($module->name !== 'Dashboard' && $module->menu_item == 1)
                                <li>
                                    <a href="#"><i class="{{ $module->icons }}"></i> {{ $module->name }}<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        @foreach ($module->pages as $page)
                                            @if ($page->menu_item == "1")
                                                <li>
                                                    <a href="{{ url($page->page_route) }}">{{ $page->page_name }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach




                    </ul>

                </div>
            </div>
        </div>
</nav>
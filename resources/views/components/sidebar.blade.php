<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            @if (!empty($masterEvent))
                @foreach ($masterEvent as $value)
                    @if (!empty($id))
                        @if ($id == Auth::user()->id)
                            <div class="sidebar-brand">
                                <a href="{{ route('dashboard', ['page' => $value['title_url']]) }}">
                                    <img src="{{ asset('images/' . $value['logo']) }}" height="54">
                                </a>
                            </div>
                            <div class="sidebar-brand sidebar-brand-sm">
                                <a href="{{ route('dashboard', ['page' => $value['title_url']]) }}">
                                    <img src="{{ asset('images/' . $value['logo']) }}" height="15">
                                </a>
                            </div>
                        @else
                            <div class="sidebar-brand">
                                <a href="{{ route('visitor_event.index', ['page' => $value['title_url']]) }}">
                                    <img src="{{ asset('images/' . $value['logo']) }}" height="54">
                                </a>
                            </div>
                            <div class="sidebar-brand sidebar-brand-sm">
                                <a href="{{ route('visitor_event.index', ['page' => $value['title_url']]) }}">
                                    <img src="{{ asset('images/' . $value['logo']) }}" height="10">
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="sidebar-brand">
                            <a href="#">
                                <img src="{{ asset('images/' . $value['logo']) }}" height="54">
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="#">
                                <img src="{{ asset('images/' . $value['logo']) }}" height="15">
                            </a>
                        </div>
                    @endif

                    @if (!empty($id))
                        @if ($id == Auth::user()->id)
                            <li class="menu-header">Dashboard</li>
                            <li class="{{ $type_menu == 'dashboard' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard', ['page' => $value['title_url']]) }}">
                                    <i class="fas fa-home"></i> <span>Dashboard</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (!empty(Auth::user()->id) && $type_menu != 'register_visitor')
                        <li class="menu-header">Master</li>
                        <li class="{{ $type_menu == 'whatsapp_event' ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('whatsapp_event.index', ['page' => $value['title_url']]) }}">
                                <i class="fab fa-whatsapp"></i> <span>WhatsApp Event </span>
                            </a>
                        </li>
                        <li class="{{ $type_menu == 'email_event' ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('email_event.index', ['page' => $value['title_url']]) }}">
                                <i class="fas fa-envelope"></i> <span>E-mail Event</span>
                            </a>
                        </li>
                        @if (!empty($id))
                            @if ($id == Auth::user()->id)
                                <li class="{{ $type_menu == 'admin_event' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('admin_event.index', ['page' => $value['title_url']]) }}"><i
                                            class="fas fa-user"></i> <span>Admin Event</span></a>
                                </li>
                            @endif
                        @endif
                        <li class="menu-header">Report</li>
                        <li class="{{ $type_menu == 'visitor_event' ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('visitor_event.index', ['page' => $value['title_url']]) }}"><i
                                    class="fas fa-eye"></i> <span>Data Visitor Event </span></a>
                        </li>
                    @endif
                @endforeach
            @else
                @if (!empty($title))
                    @if ($title != 'landing-page-qr')
                        <div class="sidebar-brand">
                            <a href="{{ route('dashboard', ['page' => 'cms']) }}">
                                <img src="{{ asset('img/datascrip-logo.png') }}" height="54">
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="{{ route('dashboard', ['page' => 'cms']) }}">
                                <img src="{{ asset('img/datascrip-logo-2.jpeg') }}" height="50">
                            </a>
                        </div>
                    @else
                        <div class="sidebar-brand">
                            <a href="#">
                                <span>View Link QR</span>
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="#">
                                <span>VLQ</span>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard', ['page' => 'cms']) }}">
                            <img src="{{ asset('img/datascrip-logo.png') }}" height="54">
                        </a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{ route('dashboard', ['page' => 'cms']) }}">
                            <img src="{{ asset('img/datascrip-logo-2.jpeg') }}" height="50">
                        </a>
                    </div>
                @endif

                @if (!empty($type_menu))
                    <li class="menu-header">Dashboard</li>
                    <li class="{{ $type_menu == 'dashboard' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard', ['page' => 'cms']) }}"><i
                                class="fas fa-home"></i>
                            <span>Dashboard</span></a>
                    </li>
                    <li class="menu-header">Master</li>

                    @if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0)
                        <li class="{{ $type_menu == 'company_event' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('company_event.index', ['page' => 'cms']) }}"><i
                                    class="fas fa-building"></i> <span>Division Event</span></a>
                        </li>
                    @endif

                    <li class="{{ $type_menu == 'master_event' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('index', ['page' => 'cms']) }}"><i
                                class="fas fa-calendar"></i>
                            <span>Master Event</span></a>
                    </li>
                    <li class="{{ $type_menu == 'whatsapp_event' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('whatsapp_event.index', ['page' => 'cms']) }}">
                            <i class="fab fa-whatsapp"></i> <span>WhatsApp Event</span>
                        </a>
                    </li>
                    <li class="{{ $type_menu == 'email_event' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('email_event.index', ['page' => 'cms']) }}">
                            <i class="fas fa-envelope"></i> <span>E-mail Event</span>
                        </a>
                    </li>
                    <li class="{{ $type_menu == 'admin_event' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin_event.index', ['page' => 'cms']) }}"><i
                                class="fas fa-user-shield"></i> <span>Admin Event</span></a>
                    </li>
                    <li class="{{ $type_menu == 'master_user' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('master_user.index', ['page' => 'cms']) }}"><i
                                class="fas fa-user-circle"></i> <span>Master User</span></a>
                    </li>
                    @if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0)
                        <li class="{{ $type_menu == 'user_access' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user_access.index', ['page' => 'cms']) }}"><i
                                    class="fas fa-users-cog"></i><span>User Access</span></a>
                        </li>
                    @endif
                    <li class="menu-header">Report</li>
                    <li class="{{ $type_menu == 'visitor_event' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('visitor_event.index', ['page' => 'cms']) }}"><i
                                class="fas fa-eye"></i> <span>Data Visitor Event</span></a>
                    </li>
                    <li class="{{ $type_menu == 'report_visitor_event' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('report_visitor_event.index', ['page' => 'cms']) }}"><i
                                class="fas fa-file-excel"></i>
                            <span>Report Visitor Event</span></a>
                    </li>
                @endif
            @endif
        </ul>
    </aside>
</div>

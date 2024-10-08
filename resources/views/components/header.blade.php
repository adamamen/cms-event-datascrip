<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">

            @if (!empty($title))
                @if ($title != 'landing-page-qr')
                    <li>
                        <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
                    </li>
                @endif
            @else
                <li>
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
                </li>

            @endif

        </ul>
    </form>
    @if (!empty(Auth::user()->full_name))
        <ul class="navbar-nav navbar-right">
            <li class="dropdown"><a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                    <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->full_name }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="margin-top: 6%;">
                    @if (!empty($masterEvent))
                        @foreach ($masterEvent as $value)
                            <a href="{{ route('logout', ['page' => $value['title_url']]) }}"
                                class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        @endforeach
                    @else
                        <a href="{{ route('logout', ['page' => 'cms']) }}" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    @endif
                </div>
            </li>
        </ul>
    @endif

</nav>

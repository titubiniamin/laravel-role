<!-- sidebar menu area start -->
@php
    use Illuminate\Support\Facades\Auth;$usr = Auth::guard('admin')->user();
@endphp
    <!-- Main Content -->

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <h2 class="text-white">Admin</h2>
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">

                    @if ($usr->can('dashboard.view'))
                        <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <a href="javascript:void(0)" aria-expanded="{{ Route::is('admin.dashboard') ? 'true' : 'false' }}">
                                <i class="ti-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                            <ul class="collapse {{ Route::is('admin.dashboard') ? 'in' : '' }}">
                                <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                            </ul>
                        </li>
                    @endif


                    <!-- Dealer Menu Section -->
                    <!-- Dealer Menu Section -->
                    @if ($usr->can('dealer.create') || $usr->can('dealer.view') || $usr->can('dealer.edit') || $usr->can('dealer.delete')|| $usr->can('dealer.import-show')||$usr->can('dealer.import'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-briefcase"></i><span>Dealers</span></a>
                            <ul class="collapse {{ Route::is('admin.dealers.create') || Route::is('admin.dealers.index') || Route::is('admin.dealers.edit') || Route::is('admin.dealers.show') || Route::is('admin.dealers.import-show') ? 'in' : '' }}">
                                @if ($usr->can('dealer.view'))
                                    <li class="{{ Route::is('admin.dealers.index') || Route::is('admin.dealers.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.dealers.index') }}">All Dealers</a>
                                    </li>
                                @endif
                                @if ($usr->can('dealer.create'))
                                    <li class="{{ Route::is('admin.dealers.create') ? 'active' : '' }}">
                                        <a href="{{ route('admin.dealers.create') }}">Create Dealer</a>
                                    </li>
                                @endif
                                @if($usr->can('dealer.import-show'))
                                    <li class="{{ Route::is('admin.dealers.import-show') ? 'active' : '' }}">
                                        <a href="{{ route('admin.dealers.import-show') }}">
                                            <i class="fa fa-map"></i> <!-- Use fa-map or fa-map-marker for map icons -->
                                            Excel Import
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <!-- Retailer Menu Section-->
                    @if ($usr->can('retailer.create') || $usr->can('retailer.view') || $usr->can('retailer.edit') || $usr->can('retailer.delete')||$usr->can('dealer.import-show')||$usr->can('dealer.import'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i
                                    class="fa fa-tag"></i><span>Retailers</span></a>
                            <ul class="collapse {{ Route::is('admin.retailers.create') || Route::is('admin.retailers.index') || Route::is('admin.retailers.edit') || Route::is('admin.retailers.show') ||Route::is('admin.retailers.show') || Route::is('admin.retailers.import-show') ? 'in' : '' }}">
                                @if ($usr->can('retailer.view'))
                                    <li class="{{ Route::is('admin.retailers.index') || Route::is('admin.retailers.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.retailers.index') }}">All Retailers</a>
                                    </li>
                                @endif
                                @if ($usr->can('retailer.create'))
                                    <li class="{{ Route::is('admin.retailers.create') ? 'active' : '' }}">
                                        <a href="{{ route('admin.retailers.create') }}">Create Retailer</a>
                                    </li>
                                @endif
                                @if($usr->can('retailer.import-show'))
                                    <li class="{{ Route::is('admin.retailers.import-show') ? 'active' : '' }}">
                                        <a href="{{ route('admin.retailers.import-show') }}">
                                            <i class="fa fa-map"></i> <!-- Use fa-map or fa-map-marker for map icons -->
                                            Excel Import
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <!--Retailer End-->

                    <!-- Central Point Menu Section-->
                    @if ($usr->can('billboard.create') || $usr->can('central-point.view') || $usr->can('central-point.edit') || $usr->can('central-point.delete'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i
                                    class="fa fa-map-pin"></i><span>Central Point</span></a>
                            <ul class="collapse {{ Route::is('admin.central-points.create') || Route::is('admin.central-points.index') || Route::is('admin.central-points.edit') || Route::is('admin.central-points.show') ? 'in' : '' }}">
                                @if ($usr->can('central-point.view'))
                                    <li class="{{ Route::is('admin.central-points.index') || Route::is('admin.central-points.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.central-points.index') }}">All Central Point</a>
                                    </li>
                                @endif
                                @if ($usr->can('central-point.create'))
                                    <li class="{{ Route::is('admin.central-points.create') ? 'active' : '' }}">
                                        <a href="{{ route('admin.central-points.create') }}">Create Central Point</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif
                    <!--Central End-->

                        <!-- Billboard Menu Section-->
                        @if ($usr->can('billboard.create') || $usr->can('billboard.view') || $usr->can('billboard.edit') || $usr->can('billboard.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-map-signs"></i><span>Billboard</span></a>
                                <ul class="collapse {{ Route::is('admin.billboards.create') || Route::is('admin.billboards.index') || Route::is('admin.billboards.edit') || Route::is('admin.billboards.show') ? 'in' : '' }}">
                                    @if ($usr->can('billboard.view'))
                                        <li class="{{ Route::is('admin.billboards.index') || Route::is('admin.billboards.edit') ? 'active' : '' }}">
                                            <a href="{{ route('admin.billboards.index') }}">All Billboards</a>
                                        </li>
                                    @endif
                                    @if ($usr->can('billboard.create'))
                                        <li class="{{ Route::is('admin.billboards.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.billboards.create') }}">Create Billboard</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        <!--Billboard End-->
                        <!-- ShopSign Menu Section-->
                        @if ($usr->can('shopsign.create') || $usr->can('shopsign.view') || $usr->can('shopsign.edit') || $usr->can('shopsign.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-delicious"></i><span>Shop Sign</span></a>
                                <ul class="collapse {{ Route::is('admin.shopsigns.create') || Route::is('admin.shopsigns.index') || Route::is('admin.shopsigns.edit') || Route::is('admin.shopsigns.show') ? 'in' : '' }}">
                                    @if ($usr->can('shopsign.view'))
                                        <li class="{{ Route::is('admin.shopsigns.index') || Route::is('admin.shopsigns.edit') ? 'active' : '' }}">
                                            <a href="{{ route('admin.shopsigns.index') }}">All Shop Signs</a>
                                        </li>
                                    @endif
                                    @if ($usr->can('shopsign.create'))
                                        <li class="{{ Route::is('admin.shopsigns.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.shopsigns.create') }}">Create Shop Sign</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        <!--Shopsign End-->
                        <!-- Highwall Menu Section-->
                        @if ($usr->can('highwall.create') || $usr->can('highwall.view') || $usr->can('highwall.edit') || $usr->can('highwall.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-align-justify"></i><span>Highwall</span></a>
                                <ul class="collapse {{ Route::is('admin.highwalls.create') || Route::is('admin.highwalls.index') || Route::is('admin.highwalls.edit') || Route::is('admin.highwalls.show') ? 'in' : '' }}">
                                    @if ($usr->can('highwall.view'))
                                        <li class="{{ Route::is('admin.highwalls.index') || Route::is('admin.highwalls.edit') ? 'active' : '' }}">
                                            <a href="{{ route('admin.highwalls.index') }}">All Highwalls</a>
                                        </li>
                                    @endif
                                    @if ($usr->can('highwall.create'))
                                        <li class="{{ Route::is('admin.highwalls.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.highwalls.create') }}">Create Highwall</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        <!--Highwall End-->

                    <!--Map-->

                    @if ($usr->can('map.analytics'))
                        <li class="{{ Route::is('admin.map.analytics') ? 'active' : '' }}">
                            <a href="{{ route('admin.map.analytics') }}">
                                <i class="fa fa-map"></i> Map Analytics
                            </a>
                        </li>
                    @endif



                    @if ($usr->can('role.create') || $usr->can('role.view') ||  $usr->can('role.edit') ||  $usr->can('role.delete'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                            Roles & Permissions
                        </span></a>
                            <ul class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                                @if ($usr->can('role.view'))
                                    <li class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.roles.index') }}">All Roles</a></li>
                                @endif
                                @if ($usr->can('role.create'))
                                    <li class="{{ Route::is('admin.roles.create')  ? 'active' : '' }}"><a
                                            href="{{ route('admin.roles.create') }}">Create Role</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                            Users
                        </span></a>
                            <ul class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">

                                @if ($usr->can('admin.view'))
                                    <li class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.admins.index') }}">All Users</a></li>
                                @endif

                                @if ($usr->can('admin.create'))
                                    <li class="{{ Route::is('admin.admins.create')  ? 'active' : '' }}"><a
                                            href="{{ route('admin.admins.create') }}">Create User</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif


                </ul>
            </nav>
        </div>
    </div>
</div>
<style>
    body {
        display: flex;
        overflow-x: hidden; /* Prevent horizontal scroll */
    }

    /* Sidebar Styles */
    .sidebar {
        width: 280px;
        height: 100vh;
        background-color: black;
        position: fixed;
        left: 0;
        top: 0;
        padding-top: 20px;
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    .sidebar a {
        padding: 10px 15px;
        display: block;
        color: #ddd;
        text-decoration: none;
    }

    .sidebar a:hover {
        background-color: #4c4c4c;
    }

    .content {
        margin-left: 250px; /* Space for sidebar */
        padding: 20px;
        transition: margin-left 0.3s ease;
    }

    .navbar-toggler {
        margin-left: 0px;
        margin-top: 0px;
        float: left;
        margin-bottom: 15px;
    }

    /* Responsive Styles */
    @media (max-width: 756px) {
        .sidebar {
            transform: translateX(-100%); /* Hide the sidebar by default */
        }

        .sidebar.open {
            transform: translateX(0); /* Show the sidebar */
        }

        .content {
            margin-left: 0; /* No margin for content when sidebar is hidden */
        }
    }
    .header-area {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding:0px;
    }

    .notification-area {
        display: flex;
        align-items: center;
    }

    .notification-area li {
        margin-left: 15px; /* Adjust spacing between icons */
    }

    .navbar-toggler {
        margin-right: auto; /* Moves the button to the left */
    }

    /* Ensuring the notification box aligns correctly */
    .bell-notify-box {
        position: absolute;
        right: 0;
        top: 40px; /* Adjust based on your design */
        width: 250px; /* Adjust the width */
    }

</style>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
    }

    // Optional: Close the sidebar when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.querySelector('.navbar-toggler');

        if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
            sidebar.classList.remove('open');
        }
    });
</script>


<!-- sidebar menu area end -->

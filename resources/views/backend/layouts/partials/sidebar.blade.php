<!-- sidebar menu area start -->
@php
    use Illuminate\Support\Facades\Auth;$usr = Auth::guard('admin')->user();
@endphp
<div class="sidebar-menu">
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
                        <li class="active">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                            <ul class="collapse">
                                <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a
                                        href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            </ul>
                        </li>
                    @endif


                    <!-- Dealer Menu Section -->
                    <!-- Dealer Menu Section -->
                    @if ($usr->can('dealer.create') || $usr->can('dealer.view') || $usr->can('dealer.edit') || $usr->can('dealer.delete'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-briefcase"></i><span>Dealers</span></a>
                            <ul class="collapse {{ Route::is('admin.dealers.create') || Route::is('admin.dealers.index') || Route::is('admin.dealers.edit') || Route::is('admin.dealers.show') ? 'in' : '' }}">
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
                    @if ($usr->can('retailer.create') || $usr->can('retailer.view') || $usr->can('retailer.edit') || $usr->can('retailer.delete'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i
                                    class="fa fa-tag"></i><span>Retailers</span></a>
                            <ul class="collapse {{ Route::is('admin.retailers.create') || Route::is('admin.retailers.index') || Route::is('admin.retailers.edit') || Route::is('admin.retailers.show') ? 'in' : '' }}">
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
                    @if ($usr->can('central-point.create') || $usr->can('central-point.view') || $usr->can('central-point.edit') || $usr->can('central-point.delete'))
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
<!-- sidebar menu area end -->

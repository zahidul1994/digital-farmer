<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{url('/')}} " >
            <img src="{{ asset(Auth::user()->image) }}" class="navbar-brand-img border-radius-sm shadow-sm" alt="main_logo">
            <br>
            <span class="ms-1 font-weight-bold">{{Auth::user()->name}}</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/dashboard*') ? 'active' : ''}}"
                    href="{{route(Request::segment(1) . '.dashboard')}}">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-palette text-primary text-sm"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
           
      <li class="nav-item">
                <a data-bs-toggle="collapse" href="#EquipmentManagement" class="nav-link" aria-controls="EquipmentManagement"
                    role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-chart-pie-35 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Equipment Manage</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/equipment-providers*') ? 'show' : ''}} "
                    id="EquipmentManagement">
                    <ul class="nav ms-4">
                        @can('equipment-provider-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/equipment-providers*') ? 'active' : ''}}"
                                href="{{route(Request::segment(1) . '.equipment-providers.index')}}">
                                <span class="sidenav-mini-icon"> AR </span>
                                <span class="sidenav-normal">Equipment Provider  </span>
                            </a>
                        </li>
                        @endcan
                        @can('set-top-box-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/set-top-box*') ? 'active' : ''}}"
                                href="{{route(Request::segment(1) . '.set-top-box.index')}}">
                                <span class="sidenav-mini-icon"> STB </span>
                                <span class="sidenav-normal">Set Tob Box </span>
                            </a>
                        </li>
                        @endcan
                        @can('smart-card-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/smart-card*') ? 'active' : ''}}"
                                href="{{route(Request::segment(1) . '.smart-card.index')}}">
                                <span class="sidenav-mini-icon"> SC </span>
                                <span class="sidenav-normal">Smart Card</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
         
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsReport" class="nav-link" aria-controls="dashboardsReport"
                    role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-chart-pie-35 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Reports</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/agent-report*') ? 'show' : ''}} "
                    id="dashboardsReport">
                    <ul class="nav ms-4">
                        @can('agent-report')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/agent-report*') ? 'active' : ''}}"
                                href="{{route(Request::segment(1) . '.agentReport')}}">
                                <span class="sidenav-mini-icon"> AR </span>
                                <span class="sidenav-normal">Agent Report </span>
                            </a>
                        </li>

                        @endcan
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsAccounts" class="nav-link"
                    aria-controls="dashboardsAccounts" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-money-coins text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Accounts</span>
                </a>
                <div class="collapse {{Request::is(Request::segment(1) .'/wallets*') ? 'show' : ''}}  {{Request::is(Request::segment(1) .'/agent-ledger*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/office-expense*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/daily-receive*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/balance-sheet*') ? 'show' : ''}}"
                    id="dashboardsAccounts">
                    <ul class="nav ms-4">

                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/wallets*') ? 'active' : ''}}"
                                href="{{route(Request::segment(1) . '.wallets.index')}}">
                                <span class="sidenav-mini-icon"> WL </span>
                                <span class="sidenav-normal">Wallet </span>
                            </a>
                        </li>

                        @can('agent-ledger-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/agent-ledger*') ? 'active' : ''}}"
                                href="{{route(Request::segment(1) . '.agent-ledger.index')}}">
                                <span class="sidenav-mini-icon"> AL </span>
                                <span class="sidenav-normal">Agent Ledger </span>
                            </a>
                        </li>
                        @endcan
                        @can('office-expense-list')
                        <li class="nav-item {{Request::is(Request::segment(1) .'/office-expense*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.office-expense.index')}}">
                                <span class="sidenav-mini-icon"> OE </span>
                                <span class="sidenav-normal">Office Expence </span>
                            </a>
                        </li>
                        @endcan
                        @can('daily-receive-list')
                        <li class="nav-item {{Request::is(Request::segment(1) .'/daily-receive*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.daily-receive.index')}}">
                                <span class="sidenav-mini-icon"> DR </span>
                                <span class="sidenav-normal">Daily Received </span>
                            </a>
                        </li>
                        @endcan
                        @can('balance-sheet')
                        <li class="nav-item {{Request::is(Request::segment(1) .'/balance-sheet*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.balanceSheet')}}">
                                <span class="sidenav-mini-icon"> BS </span>
                                <span class="sidenav-normal">Balance Sheet </span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link"
                    aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-settings text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Users & Setup</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/roles*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/users*') ? 'show' : ''}}"
                    id="dashboardsExamples">
                    <ul class="nav ms-4">

                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/roles*') ? 'active' : ''}}"
                                href="{{route(Request::segment(1) . '.roles.index')}}">
                                <span class="sidenav-mini-icon"> RO </span>
                                <span class="sidenav-normal">Roles </span>
                            </a>
                        </li>

                        @can('user-list')
                        <li class="nav-item {{Request::is(Request::segment(1) .'/users*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.users.index')}}">
                                <span class="sidenav-mini-icon"> U </span>
                                <span class="sidenav-normal">Users </span>
                            </a>
                        </li>
                        @endcan

                        <li class="nav-item {{Request::is(Request::segment(1) .'/profiles*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.profiles')}}">
                                <span class="sidenav-mini-icon"> PR </span>
                                <span class="sidenav-normal">Profile </span>
                            </a>
                        </li>

                        <li class="nav-item {{Request::is(Request::segment(1) .'/payments*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.payments')}}">
                                <span class="sidenav-mini-icon"> PA </span>
                                <span class="sidenav-normal">Payment </span>
                            </a>
                        </li>


                    </ul>
                </div>
            </li>
         

        </ul>
    </div>
    </li>


</aside>
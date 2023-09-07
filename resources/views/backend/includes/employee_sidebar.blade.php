<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{url('/')}} " target="_blank">
            <img src="{{ asset(Auth::user()->image ?: 'backend/assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">{{Auth::user()->user_type}}</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/dashboard*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.dashboard')}}">
                                <div class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-palette text-primary text-sm"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                            </a>
                    </li>
                   
                    @can('agents-list')
                    <li class="nav-item">
                        <a class="nav-link {{Request::is(Request::segment(1) .'/agents*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.agents.index')}}">
                                <div class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-user-run text-primary text-sm"></i>
                    </div>
                    <span class="nav-link-text ms-1">Agents</span>
                        </a>
                    </li>
        
                    @endcan

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#candidates" class="nav-link" aria-controls="candidates" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-badge text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Candidate</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/candidate*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/passports*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/medicals*') ? 'show' : ''}}" id="candidates">
                    <ul class="nav ms-4">
                        @can('passport-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/passports*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.passports.index')}}">
                            <span class="sidenav-mini-icon"> PA</span>
                                <span class="sidenav-normal">Passport</span>
                            </a>
                        </li>
                        @endcan
                        @can('candidate-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/candidates*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.candidates.index')}}">
                                <span class="sidenav-mini-icon"> CA </span>
                                <span class="sidenav-normal">Candidate </span>
                            </a>
                        </li>
                        @endcan
                        @can('medical-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/medicals*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.medicals.index')}}">
                   <span class="sidenav-mini-icon"> ME</span>
                                <span class="sidenav-normal">Medical </span>
                </a>
            </li>
            @endcan
                        
                        @can('candidate-flight-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/candidate-flights*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.candidate-flights.index')}}">
                               <span class="sidenav-mini-icon"> C F </span>
                                <span class="sidenav-normal">Candidate Flight </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>  

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#visaes" class="nav-link" aria-controls="visaes" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-paper-diploma text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Visa</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/visa*') ? 'show' : ''}}" id="visaes">
                    <ul class="nav ms-4">
                        @can('visa-processing-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/visa-processings*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.visa-processings.index')}}">
                  <span class="sidenav-mini-icon"> VP </span>
                    <span class="sidenav-normal">Visa Processing </span>
                </a>
            </li>
            @endcan
                      
            @can('visa-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/visa*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.visa.index')}}">
                    <span class="sidenav-mini-icon"> VS </span>
                    <span class="sidenav-normal">Visa </span>
                </a>
            </li>
            @endcan      

                    </ul>
                </div>
            </li>  

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#manPower" class="nav-link" aria-controls="manPower" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-trophy text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Man Power</span>
                </a>
                <div class="collapse {{Request::is(Request::segment(1) .'/man-powers*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/flight*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/training*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/finger*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/mofas*') ? 'show' : ''}}" id="manPower">
                    <ul class="nav ms-4">
                     
            @can('man-power-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/man-powers*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.man-powers.index')}}">
                    
                    <span class="sidenav-mini-icon"> M P </span>
                    <span class="sidenav-normal">Man Power </span>
                </a>
            </li>
            @endcan
                      
            @can('flight-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/flight*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.flight.index')}}">
                 
                    <span class="sidenav-mini-icon"> F I </span>
                    <span class="sidenav-normal">Flight Info </span>
                </a>
            </li>
            @endcan
            
            @can('training-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/training*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.training.index')}}">
                   
                    <span class="sidenav-mini-icon"> T I </span>
                    <span class="sidenav-normal">Traning Info</span>
                </a>
            </li>
            @endcan
            @can('finger-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/finger*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.finger.index')}}">
                  
                    <span class="sidenav-mini-icon"> F </span>
                    <span class="sidenav-normal">Finger </span>
                </a>
            </li>
            @endcan

            @can('mofa-list')
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/mofas*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.mofas.index')}}">
                   
                    <span class="sidenav-mini-icon"> MO </span>
                    <span class="sidenav-normal">Mofa </span>
                </a>
            </li>
            @endcan

                    </ul>
                </div>
            </li>  
           
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsReport" class="nav-link" aria-controls="dashboardsReport" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-chart-pie-35 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Reports</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/agent-report*') ? 'show' : ''}} " id="dashboardsReport">
                    <ul class="nav ms-4">
                    @can('agent-report')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/agent-report*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.agentReport')}}">
                                <span class="sidenav-mini-icon"> AR </span>
                                <span class="sidenav-normal">Agent Report </span>
                            </a>
                        </li>
                    
                        @endcan
                    </ul>
                </div>
            </li>   
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsAccounts" class="nav-link" aria-controls="dashboardsAccounts" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-money-coins text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Accounts</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/agent-ledger*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/office-expense*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/daily-receive*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/balance-sheet*') ? 'show' : ''}}" id="dashboardsAccounts">
                    <ul class="nav ms-4">
                    @can('agent-ledger-list')
                        <li class="nav-item">
                            <a class="nav-link {{Request::is(Request::segment(1) .'/agent-ledger*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.agent-ledger.index')}}">
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
          
          
        </ul>
    </div>
    </li>


</aside>
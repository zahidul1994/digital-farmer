<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{url('/')}} " target="_blank">
            <img src="{{ asset(Auth::user()->image ?: 'backend/assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100"
                alt="main_logo">
            <span class="ms-1 font-weight-bold">Pos</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link"
                    aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-badge text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Users</span>
                </a>
                <div class="collapse  {{Request::is(Request::segment(1) .'/roles*') ? 'show' : ''}} {{Request::is(Request::segment(1) .'/users*') ? 'show' : ''}}" id="dashboardsExamples">
                    <ul class="nav ms-4">
                        
                        <li class="nav-item {{Request::is(Request::segment(1) .'/roles*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.roles.index')}}">
                                <span class="sidenav-mini-icon"> R </span>
                                <span class="sidenav-normal">Roles  </span>
                            </a>
                        </li>
                        
                        <li class="nav-item {{Request::is(Request::segment(1) .'/admins*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.admins.index')}}">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal">Admins  </span>
                            </a>
                        </li>
                        <li class="nav-item {{Request::is(Request::segment(1) .'/databases*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.databases.index')}}">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal"> Database Backup </span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </li>
           
            <li class="nav-item ">
                <a class="nav-link {{Request::is(Request::segment(1) .'/packages*') ? 'active' : ''}}"  href="{{route(Request::segment(1) . '.packages.index')}}">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-diamond text-dark text-sm"></i>
                    </div>
                    <span class="nav-link-text ms-1">Package</span>
                </a>
            </li> 
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/payments*') ? 'active' : ''}}"  href="{{route(Request::segment(1) . '.payments.index')}}">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-dark text-sm"></i>
                    </div>
                    <span class="nav-link-text ms-1">Payment</span>
                </a>
            </li> 
            <li class="nav-item">
                <a class="nav-link {{Request::is('/filemanager*') ? 'active' : ''}}"
                    href="{{url('filemanager')}}" target="_blank">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-folder-17 text-dark text-sm"></i>
                    </div>
                    <span class="nav-link-text ms-1">File Manager</span>
                </a>
            </li> 
          
            <li class="nav-item {{Request::is(Request::segment(1) .'/pages*') ? 'active' : ''}}">
                <a class="nav-link"
                    href="{{route(Request::segment(1) . '.pages.index')}}">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-atom text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Page  </span>
                </a>
            </li> 
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/wallets*') ? 'active' : ''}}"
                    href="{{route(Request::segment(1) . '.wallets.index')}}">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-money-coins text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Wallet  </span>
                </a>
            </li> 
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/sliders*') ? 'active' : ''}}"
                    href="{{route(Request::segment(1) . '.sliders.index')}}">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-planet text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sliders  </span>
                </a>
            </li> 
           
            <li class="nav-item">
                <a class="nav-link {{Request::is(Request::segment(1) .'/contacts*') ? 'active' : ''}}"
                    href="{{route(Request::segment(1) . '.contacts.index')}}">
                    <div
                        class="icon icon-shape icon-sm text-center  me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-copy-04 text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Contact </span>
                </a>
            </li> 
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link"
                    aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-ui-04 text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Video</span>
                </a>
                <div class="collapse   {{Request::is(Request::segment(1) .'/video-uploads*') ? 'show' : ''}}"   id="dashboardsExamples">
                    <ul class="nav ms-4">
                     
                        <li class="nav-item {{Request::is(Request::segment(1) .'/video-uploads*') ? 'active' : ''}}">
                            <a class="nav-link " href="{{route(Request::segment(1) . '.video-uploads.index')}}">
                                <span class="sidenav-mini-icon"> V </span>
                                <span class="sidenav-normal">Video  </span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </li>
                       
        <li class="nav-item">
            <a data-bs-toggle="collapse" href="#settingEX" class="nav-link"
                aria-controls="settingEX" role="button" aria-expanded="false">
                <div class="icon icon-bar icon-sm text-center d-flex align-items-center justify-content-center">
                    <i class="ni ni-settings text-info text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Settings</span>
            </a>
            <div class="collapse  {{Request::is(Request::segment(1) .'/profiles*') ? 'show' : ''}}  {{Request::is(Request::segment(1) .'/payments*') ? 'show' : ''}}  {{Request::is(Request::segment(1) .'/setting*') ? 'show' : ''}}{{Request::is(Request::segment(1) .'/artisan-command*') ? 'show' : ''}}{{Request::is(Request::segment(1) .'/smtp-settings*') ? 'show' : ''}}{{Request::is(Request::segment(1) .'/divisions*') ? 'show' : ''}}{{Request::is(Request::segment(1) .'/districts*') ? 'show' : ''}}{{Request::is(Request::segment(1) .'/thanas*') ? 'show' : ''}}"
                id="settingEX">
                <ul class="nav ms-4">
                    <li class="nav-item {{Request::is(Request::segment(1) .'/profiles*') ? 'active' : ''}}">
                        <a class="nav-link " href="{{route(Request::segment(1) . '.profiles')}}">
                            <span class="sidenav-mini-icon"> P </span>
                            <span class="sidenav-normal">Profile </span>
                        </a>
                    </li>
                    <li class="nav-item {{Request::is(Request::segment(1) .'/setting*') ? 'active' : ''}}">
                        <a class="nav-link " href="{{url(Request::segment(1) . '/setting')}}">
                            <span class="sidenav-mini-icon"> P </span>
                            <span class="sidenav-normal">Setting </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Request::is(Request::segment(1) .'/artisan-command*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.artisanCommand')}}">
                            <span class="sidenav-mini-icon"> P </span>
                            <span class="sidenav-normal">Command </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Request::is(Request::segment(1) .'/smtp-settings*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.smtpIndex')}}">
                            <span class="sidenav-mini-icon"> P </span>
                            <span class="sidenav-normal">SMTP </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Request::is(Request::segment(1) .'/divisions*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.divisions.index')}}">
                            <span class="sidenav-mini-icon"> D </span>
                            <span class="sidenav-normal">Division </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Request::is(Request::segment(1) .'/districts*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.districts.index')}}">
                            <span class="sidenav-mini-icon"> D </span>
                            <span class="sidenav-normal">District </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Request::is(Request::segment(1) .'/thanas*') ? 'active' : ''}}" href="{{route(Request::segment(1) . '.thanas.index')}}">
                            <span class="sidenav-mini-icon"> T </span>
                            <span class="sidenav-normal">Thana </span>
                        </a>
                    </li>
                    
                </ul>
            </div>
        </li>
            
        </ul>
    </div>
    
</aside>
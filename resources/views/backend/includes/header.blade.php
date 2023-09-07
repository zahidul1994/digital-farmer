<nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky " id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            @if (isset($breadcrumbs))
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    @foreach ($breadcrumbs as $breadcrumb) 
                    <li class="breadcrumb-item text-sm text-white {{ !isset($breadcrumb['link']) ? 'active' : '' }}">
                        @if (isset($breadcrumb['link']) && $breadcrumb['link'] !== 'javascript:void(0)')
                        <a class="opacity-5 text-white" href="{{ url($breadcrumb['link']) }}">
                            @endif  @if ($loop->first) <i class="ni ni-box-2"></i> @endif {{ $breadcrumb['name'] }}@if(isset($breadcrumb['link'])) 
                        </a>
                        @endif
                    </li>
                    @endforeach
                </ol>
                @endif
            
            
          </nav>
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
            <a href="javascript:;" class="nav-link p-0">
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
              </div>
            </a>
          </div>
          <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="text-white bg-primary mb-0 shadow-lg px-3 py-2 rounded"  id="MyClockDisplay">
                     </div>
                </div>
            </div>
            <ul class="navbar-nav  justify-content-end ">
            <li class="nav-item d-flex pe-2 align-items-center">
                    <a href="javascript:;" class="nav-link text-white pr-1" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        
                       <i class="fa fa-bell cursor-pointer" id="seennotify"><sup class="text-danger">{{ auth()->user()->unreadNotifications->count()>0 }}</sup> </i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        @foreach(auth()->user()->unreadNotifications as $notification)
    
                        <li>
    
                            <a class="dropdown-item border-radius-md" href="{{route(Request::segment(1).'.notifications.index')}}">
                                <div class="d-flex py-1">
    
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold"> {!! ($notification->data['data']) !!}</span>
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            {{($notification->created_at)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
    
                        </li>
                        @endforeach
                        @foreach(auth()->user()->readNotifications as $notification)
    
                        <li class="nav-item d-flex pe-2 align-items-center">
    
                            <a class="dropdown-item border-radius-md" href="{{route(Request::segment(1).'.notifications.index')}}">
                                <div class="d-flex py-1">
    
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold"> {!! ($notification->data['data']) !!} </span>
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            {{($notification->created_at)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
    
                        </li>
                        @endforeach
    
                    </ul>
                   
                </li>
                
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center ">
                    <a href="javascript:;" class="nav-link text-white pr-1" id="iconNavbarSidenav">
                      <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line bg-white"></i>
                        <i class="sidenav-toggler-line bg-white"></i>
                        <i class="sidenav-toggler-line bg-white"></i>
                      </div>
                    </a>
                    
                  </li>
             
               
              
                <li class="nav-item d-flex pe-2 align-items-center">
                    <a title="Calculator" class="av-link text-white font-weight-bold pr-1" data-bs-toggle="modal" data-bs-target="#calculatorModal">
                        <i class="fa fa-calculator" aria-hidden="true"></i>
                      </a>
                      
                </li>
                <li class="nav-item dropdown d-flex pe-1 align-items-center">
          <a class="nav-link dropdown-toggle av-link text-white font-weight-bold pr-1" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          {{Auth::user()->name}}
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
            <li><a class="dropdown-item" href="{{route(Request::segment(1) . '.profiles')}}"><i class="fas fa-user me-sm-1"></i><span class="d-sm-inline d-none"  ></span> Profile</a></li>
            
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('logout') }}" title="Sing Out Button" onclick="event.preventDefault();
               document.getElementById('logout-form').submit();"><i class="ni ni-key-25 me-sm-1"></i><span class="d-sm-inline d-none"  ></span> Log Out </a>   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form></li>
          </ul>
        </li>
              
              
            </ul>
        </div>
    </div>
</nav>

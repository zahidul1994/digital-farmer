
@extends('backend.layouts.master')
@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Employee</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter1" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                <span >Go</span>
                                                to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                            <i class="ni ni-user-run text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Shop
                                            </p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter2" countTo="0"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to  List   </a>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                            <i class="ni ni-shop text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Purc</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter3" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                            <i class="ni ni-vector text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Mofa</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter4" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                            <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Applications</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter5" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                            <i class="ni ni-collection text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Biodata
                                            </p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter6" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                            <i class="ni ni-single-copy-04 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Candidate</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter7" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                            <i class="ni ni-hat-3 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Departure Card</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter8" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                            <i class="ni ni-satisfied text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Finger</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter9" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                            <i class="ni ni-controller text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Training
                                            </p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter10" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                            <i class="ni ni-air-baloon text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Parent Objection</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter11" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                            <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Passport</p>
                                            <h5 class="font-weight-bolder">
                                                <span class="small"> </span><span id="counter12" countTo="{{count($users)}}"></span>
                                               
                                            </h5>
                                            <p class="mb-0">
                                                <a class="text-success text-sm font-weight-bolder" href="{{ route(Request::segment(1) . '.users.index') }}">
                                                    <span >Go</span>
                                                    to List   </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Income  overview</h6>
                       
                         
                        <p class="text-sm mb-0">
                         
                                <i class="fa fa-arrow-up text-success"></i>
                                <span class="font-weight-bold"> {{date('Y')-1}} To </span>  {{date('Y')}}
                            
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner border-radius-lg h-100">
                            @foreach ($sliders as $slider)
                            @if($loop->index==0)
                            
                            <div class="carousel-item h-100 active"
                                style="background-image: url('{{@$slider->image}}'); background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-camera-compact text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1"><a href="{{@$slider->link}}"> {{@$slider->link_text}} </a></h5>
                                   
                                </div>
                            </div>
                          @else
                          <div class="carousel-item h-100"
                          style="background-image: url('{{@$slider->image}}'); background-size: cover;">
                          <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                              <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                  <i class="ni ni-camera-compact text-dark opacity-10"></i>
                              </div>
                              <h5 class="text-white mb-1"><a href="{{@$slider->link}}"> {{@$slider->link_text}} </a></h5>
                              
                          </div>
                      </div> 
                            @endif
                            @endforeach
                       
                        </div>
                        <button class="carousel-control-prev w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

      
    </div>
        <!-- Messenger Chat plugin Code -->
<div id="fb-root"></div>

<!-- Your Chat plugin code -->
<div id="fb-customer-chat" class="fb-customerchat"></div>
@endsection
@push('js')
	 <script src="{{ asset('backend/assets/js/plugins/countup.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    $(document).ready(function () {
        for (let i = 0; i <20; i++) {
            if (document.getElementById('counter'+i)) {
      const countUp = new CountUp('counter'+i, document.getElementById('counter'+i).getAttribute("countTo"));
      if (!countUp.error) {
        countUp.start();
      } else {
        console.error(countUp.error);
      }
    }
        }
        
     // Count To
       var ctx1 = document.getElementById("chart-line").getContext("2d");
       var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
       currentyeardata=@json(($currentyear)); 
       previewyear=@json(($previewyear)); 
       var previousYears = {
            Jan : previewyear[1],
            Feb: previewyear[2],
            Mar: previewyear[3],
            Apr: previewyear[4],
            May: previewyear[5],
            Jun: previewyear[6],
            Jul: previewyear[7],
            Aug: previewyear[8],
            Sep: previewyear[9],
            Oct: previewyear[10],
            Nov: previewyear[11],
            Dec: previewyear[12]
       };
       var currentdateHash = {
            Jan : currentyeardata[1],
            Feb: currentyeardata[2],
            Mar: currentyeardata[3],
            Apr: currentyeardata[4],
            May: currentyeardata[5],
            Jun: currentyeardata[6],
            Jul: currentyeardata[7],
            Aug: currentyeardata[8],
            Sep: currentyeardata[9],
            Oct: currentyeardata[10],
            Nov: currentyeardata[11],
            Dec: currentyeardata[12]
      };



        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
       
        new Chart(ctx1, {
            data: {
            datasets: [{
            type: "bar",
            label: "Previous Year",
            weight: 5,
            tension: 0.4,
            borderWidth: 0,
            pointBackgroundColor: "#3A416F",
            borderColor: "#3A416F",
            backgroundColor: '#3A416F',
            borderRadius: 4,
            borderSkipped: false,
            data:previousYears,
            maxBarThickness: 10,
          },
          {
            type: "line",
            label: "Current Year",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            pointBackgroundColor: "#5e72e4",
            borderColor: "#5e72e4",
            borderWidth: 3,
            backgroundColor:gradientStroke1 ,
            data:currentdateHash,
            fill: true,
          }
        ],
                
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "102392852423371");
        chatbox.setAttribute("attribution", "biz_inbox");
            window.fbAsyncInit = function() {
          FB.init({
            xfbml            : true,
            version          : 'v13.0'
          });
        };
      
        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
          


    
    });
    </script>
@endpush

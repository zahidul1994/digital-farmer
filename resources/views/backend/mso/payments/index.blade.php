@extends('backend.layouts.msomaster')
@section('title', 'Payment')
@push('css')
<style>
        
    /* COUNTDOWN TIMER */
    .countdown .countdown-item {
        display: inline-block;
    }

    .countdown .countdown-digit,
    .countdown .countdown-label {
        font-size: 2rem;
        font-weight: 300;
        font-family: "Open Sans", sans-serif;
    }

    .countdown .countdown-label {
        font-size: 1.2rem;
        padding: 0 10px;
    }

    .countdown-sm .countdown-digit,
    .countdown-sm .countdown-label {
        font-size: 1.4rem;
    }

    .countdown-sm .countdown-label {
        font-size: 0.875rem;
        padding: 0 10px;
    }

    [data-countdown-label="hide"] .countdown-label:not(.countdown-days) {
        display: none;
    }

    [data-countdown-label="show"] .countdown-separator {
        display: none;
    }

    .countdown--style-1 .countdown-item {
        margin-right: 10px;
    }

    .countdown--style-1 .countdown-item:last-child {
        margin-right: 0;
    }

    .countdown--style-1 .countdown-digit {
        display: block;
        width: 60px;
        height: 60px;
        background: #f3f3f3;
        color: #333;
        font-size: 22px;
        font-weight: 400;
        text-align: center;
        line-height: 60px;
        font-family: "Open Sans", sans-serif;
    }

    .countdown--style-1 .countdown-label {
        display: block;
        margin-top: 5px;
        text-align: center;
        font-size: 13px;
        font-weight: 500;
        font-family: "Open Sans", sans-serif;
        text-transform: uppercase;
    }

    .countdown--style-1-v1 .countdown-digit {
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
    }
</style>
 @endpush
@section('content')
<div class="container-fluid my-5 py-2">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-xl-6 mb-xl-0 mb-4">
            <div class="card bg-transparent shadow-xl">
              <div class="overflow-hidden position-relative border-radius-xl" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/card-visa.jpg');">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 p-3">
                  <i class="fas fa-wifi text-white p-2"></i>
                  <h5 class="text-white mt-4 mb-5 pb-2"> <div class="countdown countdown-sm countdown--style-1"
                    data-countdown-date="{{ date('m/d/Y', strtotime(@$userInfo->account_expire_date)), date('m/d/Y') }}"
                    data-countdown-label="show"></div></h5>
                  <div class="d-flex">
                    <div class="d-flex">
                      <div class="me-4">
                        <p class="text-white text-sm opacity-8 mb-0">Hi  @if ($userInfo->profile->gender=='Male')
                            Mr @else Miss
                        @endif</p>
                        <h6 class="text-white mb-0">{{$userInfo->name}} </h6>
                      </div>
                      <div>
                        <p class="text-white text-sm opacity-8 mb-0">Expires</p>
                        <h6 class="text-white mb-0">{{(@$userInfo->account_expire_date)}}</h6>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6">
            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header mx-4 p-3 text-center">
                    <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                      <i class="fas fa-landmark opacity-10"></i>
                    </div>
                  </div>
                  <div class="card-body pt-0 p-3 text-center">
                    <h6 class="text-center mb-0">Balance</h6>
                    <span class="text-xs">Main Balance</span>
                    <hr class="horizontal dark my-3">
                    <h5 class="mb-0">BDT {{@Auth::user()->balance}} TK</h5>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mt-md-0 mt-4">
                <div class="card">
                  <div class="card-header mx-4 p-3 text-center">
                    <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                      <i class="fab fa-paypal opacity-10"></i>
                    </div>
                  </div>
                  <div class="card-body pt-0 p-3 text-center">
                    <h6 class="text-center mb-0">Wallet</h6>
                    <span class="text-xs">Pending Amount</span>
                    <hr class="horizontal dark my-3">
                    <h5 class="mb-0">BDT {{(Helper::getBlance($userInfo->id)->where('status',0)->sum('credit'))}} TK</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 mb-lg-0 mb-4">
            @include('partial.formerror')
            {!! Form::open(['route' => Request::segment(1) . '.paymentsCreate', 'method' => 'POST', 'files' => true]) !!}
            <div class="card mt-4">
              <div class="card-header pb-0 p-3">
                <div class="row">
                  <div class="col-6 d-flex align-items-center">
                    <h6 class="mb-0">Payment Method</h6>
                  </div>
                  <div class="col-6 d-flex align-items-center">
                    {!! Form::Number('amount', null, ['id' => 'amount','placeholder' =>'Type Payment Amount', 'class' => 'mx-auto form-control', 'required']) !!}
                    @if ($errors->has('amount'))
                    <span class="text-danger alert">{{ $errors->first('amount') }}</span>
                    @endif
                  </div>
                  
                </div>
              </div>
              <div class="card-body p-3">
                <div class="row">
                  @foreach ($payentmethod as $method)
                  <div class="col-md-6 mb-md-0 mb-4 mt-2">
                    <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                      <button name="payment"  data-bs-toggle="tooltip" data-bs-placement="top" title="Pay with" value="{{$method->id}}">
                      <img class="img-fluid w-100" src="{{@$method->image}}" alt="{{$method->payment_name}}" style="max-width:310px; max-height:170px">
                      <h6 class="mb-0 text-center">{{$method->payment_name}}</h6>
                    </button>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <div class="row">
              <div class="col-6 d-flex align-items-center">
                <h6 class="mb-0">Invoices</h6>
              </div>
              <div class="col-6 text-end">
                <button class="btn btn-outline-primary btn-sm mb-0">View All</button>
              </div>
            </div>
          </div>
          <div class="card-body p-3 pb-0">
            <ul class="list-group">
              @foreach ($wallets as $wallet)
           
              <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                <div class="d-flex flex-column">
                  <h6 class="mb-1 text-dark font-weight-bold text-sm">{{$wallet->created_at}}</h6>
                  <span class="text-xs">#SB-{{date('Y').$wallet->id}}</span>
                </div>
                <div class="d-flex align-items-center text-sm">
                  TK {{$wallet->credit}}
                  <a href="{{ route(Request::segment(1) . '.downloadInvoice',encrypt($wallet->id)) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</a>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-md-7">
        <div class="card">
          <div class="card-header pb-0 px-3">
            <h6 class="mb-0">Billing Information</h6>
          </div>
        
            <div class="card z-index-2 h-100">
              <div class="card-header pb-0 pt-3 bg-transparent">
                  <h6 class="text-capitalize">Payment  overview</h6>
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
      </div>
      <div class="col-md-5 mt-md-0 mt-4">
        <div class="card h-100 mb-4">
          <div class="card-header pb-0 px-3">
            <div class="row">
              <div class="col-md-6">
                <h6 class="mb-0">Your Transaction's</h6>
              </div>
              <div class="col-md-6 d-flex justify-content-end align-items-center">
                <i class="far fa-calendar-alt me-2"></i>
                <small>{{ date('Y-m-d') }}</small>
              </div>
            </div>
          </div>
          <div class="card-body pt-4 p-3">
            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Newest</h6>
            <ul class="list-group">
              <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                <div class="d-flex align-items-center">
                  <button class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-arrow-down"></i></button>
                  <div class="d-flex flex-column">
                    <h6 class="mb-1 text-dark text-sm">Netflix</h6>
                    <span class="text-xs">27 March 2020, at 12:30 PM</span>
                  </div>
                </div>
                <div class="d-flex align-items-center text-danger text-gradient text-sm font-weight-bold">
                  - $ 2,500
                </div>
              </li>
            
            </ul>
          
          </div>
        </div>
      </div>
    </div>
  
  </div>

@endsection
@push('js')
<script src="{{ asset('backend/assets/js/plugins/countup.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/countdown.js') }}"></script>
<script>
    $(document).ready(function() {
            if ($(".countdown").length > 0) {
                $(".countdown").each(function() {
                    var $this = $(this);
                    var date = $this.data("countdown-date");
                    $this.countdown(date).on("update.countdown", function(event) {
                        var $this = $(this).html(event.strftime("" +
                            '<div class="countdown-item bg-gradient-primary shadow"><span class="countdown-digit">%-D</span><span class="countdown-label countdown-days round">day%!d</span></div>' +
                            '<div class="countdown-item bg-gradient-primary shadow"><span class="countdown-digit">%H</span><span class="countdown-separator">:</span><span class="countdown-label">hr</span></div>' +
                            '<div class="countdown-item bg-gradient-primary shadow"><span class="countdown-digit">%M</span><span class="countdown-separator">:</span><span class="countdown-label">min</span></div>' +
                            '<div class="countdown-item bg-gradient-primary shadow"><span class="countdown-digit">%S</span><span class="countdown-label">sec</span></div>'
                            ));
                    });
                });
            };



              // chart To
       var ctx1 = document.getElementById("chart-line").getContext("2d");
       var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
       approvepaymentdata=@json(($approvepayment)); 
      
       var approvePayments = {
            Jan : approvepaymentdata[1],
            Feb: approvepaymentdata[2],
            Mar: approvepaymentdata[3],
            Apr: approvepaymentdata[4],
            May: approvepaymentdata[5],
            Jun: approvepaymentdata[6],
            Jul: approvepaymentdata[7],
            Aug: approvepaymentdata[8],
            Sep: approvepaymentdata[9],
            Oct: approvepaymentdata[10],
            Nov: approvepaymentdata[11],
            Dec: approvepaymentdata[12]
       };
       pendingpaymentdata=@json(($pendingpayment)); 
       var pendingPayments = {
            Jan : pendingpaymentdata[1],
            Feb: pendingpaymentdata[2],
            Mar: pendingpaymentdata[3],
            Apr: pendingpaymentdata[4],
            May: pendingpaymentdata[5],
            Jun: pendingpaymentdata[6],
            Jul: pendingpaymentdata[7],
            Aug: pendingpaymentdata[8],
            Sep: pendingpaymentdata[9],
            Oct: pendingpaymentdata[10],
            Nov: pendingpaymentdata[11],
            Dec: pendingpaymentdata[12]
      };



        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
       
        new Chart(ctx1, {
            data: {
            datasets: [{
            type: "bar",
            label: "Approve Amount",
            weight: 5,
            tension: 0.4,
            borderWidth: 0,
            pointBackgroundColor: "#3A416F",
            borderColor: "#3A416F",
            backgroundColor: '#3A416F',
            borderRadius: 4,
            borderSkipped: false,
            data:approvePayments,
            maxBarThickness: 10,
          },
          {
            type: "line",
            label: "Pending Amount",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            pointBackgroundColor: "#5e72e4",
            borderColor: "#077E8C",
            borderWidth: 3,
            backgroundColor:gradientStroke1 ,
            data:pendingPayments,
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




        });
</script>
@endpush

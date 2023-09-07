@extends('backend.layouts.master')
@section('title', 'Show Purchase')
@push('css')

@endpush
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-lg-5">
    <div class="col-lg-8 mx-auto">
    <div class="card my-5">
    <div class="card-header p-3 pb-0">
    <div class="d-flex justify-content-between align-items-center">
    <div>
    <h6>Order Details</h6>
    <p class="text-sm mb-0">
    Order no. <b>241342</b> from <b>23.02.2021</b>
    </p>
    <p class="text-sm">
    Code: <b>KF332</b>
    </p>
    </div>
    <a href="{{ url()->previous()}}" class="btn bg-gradient-secondary ms-auto mb-0">back</a>
    </div>
    </div>
    <div class="card-body p-3 pt-0">
    <hr class="horizontal dark mt-0 mb-4">
    <div class="row">
    
    <hr class="horizontal dark mt-4 mb-4">
    <div class="row">
    <div class="col-lg-3 col-md-6 col-12">
    <h6 class="mb-3">Track order</h6>
    <div class="timeline timeline-one-side">
    <div class="timeline-block mb-3">
    <span class="timeline-step">
    <i class="ni ni-bell-55 text-secondary"></i>
    </span>
    <div class="timeline-content">
    <h6 class="text-dark text-sm font-weight-bold mb-0">Order received</h6>
    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 7:20 AM</p>
    </div>
    </div>
    <div class="timeline-block mb-3">
    <span class="timeline-step">
    <i class="ni ni-html5 text-secondary"></i>
    </span>
    <div class="timeline-content">
    <h6 class="text-dark text-sm font-weight-bold mb-0">Generate order id #1832412</h6>
    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 7:21 AM</p>
    </div>
    </div>
    <div class="timeline-block mb-3">
    <span class="timeline-step">
    <i class="ni ni-cart text-secondary"></i>
    </span>
    <div class="timeline-content">
    <h6 class="text-dark text-sm font-weight-bold mb-0">Order transmited to courier</h6>
    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 8:10 AM</p>
    </div>
    </div>
    <div class="timeline-block mb-3">
    <span class="timeline-step">
    <i class="ni ni-check-bold text-success text-gradient"></i>
    </span>
    <div class="timeline-content">
    <h6 class="text-dark text-sm font-weight-bold mb-0">Order delivered</h6>
    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 4:54 PM</p>
    </div>
    </div>
    </div>
    </div>
    <div class="col-lg-5 col-md-6 col-12">
    <h6 class="mb-3">Payment details</h6>
    <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
    <img class="w-10 me-3 mb-0" src="../../../assets/img/logos/mastercard.png" alt="logo">
    <h6 class="mb-0">****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;7852</h6>
    <button type="button" class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center ms-auto" data-bs-toggle="tooltip" data-bs-placement="bottom" title data-bs-original-title="We do not store card details">
    <i class="fas fa-info" aria-hidden="true"></i>
    </button>
    </div>
    <h6 class="mb-3 mt-4">Billing Information</h6>
    <ul class="list-group">
    <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
    <div class="d-flex flex-column">
    <h6 class="mb-3 text-sm">Oliver Liam</h6>
    <span class="mb-2 text-xs">Company Name: <span class="text-dark font-weight-bold ms-2">Viking Burrito</span></span>
    <span class="mb-2 text-xs">Email Address: <span class="text-dark ms-2 font-weight-bold"><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="6e010207180b1c2e0c1b1c1c071a01400d0103">[email&#160;protected]</a></span></span>
    <span class="text-xs">VAT Number: <span class="text-dark ms-2 font-weight-bold">FRB1235476</span></span>
    </div>
    </li>
    </ul>
    </div>
    <div class="col-lg-3 col-12 ms-auto">
    <h6 class="mb-3">Order Summary</h6>
    <div class="d-flex justify-content-between">
    <span class="mb-2 text-sm">
    Product Price:
    </span>
    <span class="text-dark font-weight-bold ms-2">$90</span>
    </div>
    <div class="d-flex justify-content-between">
    <span class="mb-2 text-sm">
    Delivery:
    </span>
    <span class="text-dark ms-2 font-weight-bold">$14</span>
    </div>
    <div class="d-flex justify-content-between">
    <span class="text-sm">
    Taxes:
    </span>
    <span class="text-dark ms-2 font-weight-bold">$1.95</span>
    </div>
    <div class="d-flex justify-content-between mt-4">
    <span class="mb-2 text-lg">
    Total:
    </span>
    <span class="text-dark text-lg ms-2 font-weight-bold">$105.95</span>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
   
    </div>
@endsection
@push('js')

<script>
   


</script>

@endpush
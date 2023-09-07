@extends('backend.layouts.master')
@section('title', 'Supplier Due Payment')
@push('css')
<link href="{{ asset('backend/assets/select2/css/select2.min.css') }}" rel="stylesheet" />
<style>
    .select2-selection__choice {
        background-color: var(--bs-gray-200);
        border: none !important;
        font-size: 12px;
        font-size: 0.85rem !important;
    }
</style>
@endpush
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header pb-4">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Supplier Payment</p>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @include('partial.formerror')
                        {!! Form::model($supplier, [
                          'route' => [Request::segment(1) . '.supplier-due.update', $supplier->id],
                          'method' => 'PATCH',
                          'id' => 'formReset'
                      ]) !!}
                        
                         @include('backend.common.supplier_dues.form')
                        
                        <div class="text-center mt-3">
                            <a href="{{ url()->previous()}}" class="btn btn-success">
                                <i class="fa fa-backward"> </i> Back

                            </a>
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Update</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<!-- select2 init -->
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>

<script>
$(".select2").select2();

 var supplier = $('#supplier_id').val();
$.ajax({
        type: "GET",
        url: url + '/get-supplier-due/'+supplier,
        dataType: "JSON",
        success:function(data) {
         if(data){
          $('#due').val(data);
               
          }
            },
    });
  
if($('#payment_method').val()==2){
$('#MobileBankPayment').removeClass('d-none');
$('#BankPayment').addClass('d-none');
 }else if($('#payment_method').val()==3){
  $('#BankPayment').removeClass('d-none');
  $('#MobileBankPayment').addClass('d-none');
 }else{
  $('#MobileBankPayment').addClass('d-none');
  $('#BankPayment').addClass('d-none');

 }

//supplier_id change
$('#supplier_id').change(function(){
  $('#due').empty();
 var supplier = $(this).val();
$.ajax({
        type: "GET",
        url: url + '/get-supplier-due/'+supplier,
        dataType: "JSON",
        success:function(data) {
         if(data){
          $('#due').val(data);
               
          }
            },
    });
  });

$('#payment_method').change(function(){

 if($(this).val()==2){
$('#MobileBankPayment').removeClass('d-none');
$('#BankPayment').addClass('d-none');
 }else if($(this).val()==3){
  $('#BankPayment').removeClass('d-none');
  $('#MobileBankPayment').addClass('d-none');
 }else{
  $('#MobileBankPayment').addClass('d-none');
  $('#BankPayment').addClass('d-none');

 }
  });
</script>

@endpush
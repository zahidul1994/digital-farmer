@extends('backend.layouts.master')
@section('title', 'Cash Payment Bkash')
@push('css')
   
@endpush
@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header pb-4">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Bkash Payment</p>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group">
                            <label for="amount" class="form-control-label">Amount * </label>
                            {!! Form::text('amount', Session::get('bkash_amount'), ['id' => 'price', 'class' => 'form-control', 'readonly']) !!}
                          
                        </div>
                            <div class="text-center mt-3">
                                <a href="{{ url()->previous()}}"
                                    class="btn btn-success">
                                        <i class="fa fa-backward"> </i>  Back
                                    
                                </a>
                                <button class="btn btn-info" id="bKash_button">
                                    Pay with bKash
                                </button>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script id="myScript" src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>

<script>
  
        var paymentID = '';
        bKash.init({
            paymentMode: 'checkout', 
            paymentRequest: {
                amount: "{{Session::get('bkash_amount')}}",
                intent: 'sale'
            },
            createRequest: function(request) { 
                $.ajax({
                    url: '/bkash/create',
                    type: 'POST',
                    data: JSON.stringify(request),
                    contentType: 'application/json',
                    success: function(data) {

                        console.log(data)
                        var bkashData =JSON.parse(data);
                        if (bkashData && bkashData.paymentID != null) {
                            paymentID = bkashData.paymentID;
                            bKash.create().onSuccess(bkashData); //pass the whole response data in bKash.create().onSucess() method as a parameter
                        } else {
                            bKash.create().onError();
                        }
                    },
                    error: function() {
                        bKash.create().onError();
                    }
                });
            },
            executeRequestOnAuthorization: function() {
                console.log("execute working !!")
                $.ajax({
                    url: '/bkash/execute',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        "paymentID": paymentID
                    }),
                    success: function(data) {

                        console.log("execution response" , data)
                        var bkashData =JSON.parse(data);
                        if (bkashData && bkashData.paymentID != null) {
                            console.log("success")
                            console.log("trxID: ",bkashData.trxID)
                            //window.location.href = `success.html?trxID=${data.trxID}`;//Merchant’s success page
                            window.location.href = '/bkash/success'; // Your redirect route when successful payment
                        } else {
                            console.log("error ");
                            window.location.href = '/bkash/fail'; // Your redirect route when fail payment
                            bKash.execute().onError();
                        }
                    },
                    error: function() {
                        bKash.execute().onError();
                    }
                });
            },
            onClose: function(){
                window.location.href='/';  // Your redirect route when cancel payment
            },
        });

        function clickPayButton() {
            $("#bKash_button").trigger('click');
        }
  </script>

@endpush


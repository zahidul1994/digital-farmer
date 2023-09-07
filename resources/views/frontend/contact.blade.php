@extends('layouts.app')
@push('css')
@endpush
@section('content')

<!--============================= RESERVE A SEAT =============================-->
<section class="main-block">
    <div class="container">
        <div class="main-most-popular">
            <h2 class="browse-bikes-head">
                Contact Us
            </h2>

            <div class="contact-box">
                @include('partial.laravelmessage')

                <div class="row">
                    <div class="col-md-6">
                        {!! Form::open(array('url' => 'contact','method'=>'POST')) !!}

                        <div class="form-floating mb-3">
                            <label for="name">Name *</label>
                            {!!Form::text('name',@Auth::user()->fullname,
                            array('id'=>'name','required','class'=>'form-control'))!!}
                            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif

                        </div>
                        <div class="form-floating mb-3">
                            <label for="email">Email Address *</label>
                            {!!Form::email('email',@Auth::user()->email,
                            array('id'=>'email','required','class'=>'form-control'))!!}
                            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif

                        </div>
                        <div class="form-floating mb-3">
                            <label for="message">Message</label>
                            {!!Form::textarea('message',null,
                            array('id'=>'message','required','class'=>'form-control'))!!}

                        </div>
                        <div class="form-check mb-3">
                            <span id="firstVal"></span>+<span id="secondVal"></span> = 
                            <input type="number" required class="form-controll" style="width:60px" id="sumValue">
                       
                          <button type="submit" class="btn btn-primary btn-sm mb-1" disabled id="submit">Submit</button>
                        {!! Form::close() !!}
                    </div>
                    </div>
                    <div class="col-md-6">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d21604863.487111375!2d-117.0296683466655!3d35.683661403073174!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e1!3m2!1sen!2sbd!4v1682854705280!5m2!1sen!2sbd"
                            width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        <div class="address">
                            <h3 class="mb-0">
                                Contact Address
                            </h3>
                            <p class="mb-0">
                                Address: # {{Helper::setting()->address}} <br>

                                Phone: {{Helper::setting()->phone}}
                            </p>

                            <p><a href="mailto:{{Helper::setting()->email}} ">{{Helper::setting()->email}} </a></p>

                        </div>

                    </div>
                </div>

            </div>



        </div>

    </div>

</section>

@endsection
@push('js')

<script>
    $(document).ready(function () {
           // for first value 
             var firstnum = Math.floor(1 + (Math.random() * Math.pow(8, 1)));
            $('#firstVal').html(firstnum);
               
             // for second value 
             var secondnum = Math.floor(1 + (Math.random() * Math.pow(8, 1)));
            $('#secondVal').html(secondnum);
          var sumValue=firstnum+secondnum;
            $('#sumValue').on('keyup keypress', function(e) {
                typeVal=$(this).val();
                if(sumValue==typeVal){
              $('#submit').prop("disabled", false);
                }
                else{
                    $('#submit').prop("disabled", true);
                }
            ;
        });

    });
</script>


@endpush